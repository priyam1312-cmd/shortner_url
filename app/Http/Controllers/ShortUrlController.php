<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ShortUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['redirect']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ShortUrl::with(['user', 'company']);

        // Authorization rules
        if ($user->isSuperAdmin()) {
            // SuperAdmin can't see all URLs for every company at once
            // They can only view filtered URLs by date interval
            if (!$request->has('date_filter') || empty($request->date_filter)) {
                // If no date filter, show empty result or redirect
                $shortUrls = ShortUrl::whereRaw('1 = 0')->paginate(10);
                return view('urls.index', compact('shortUrls'));
            }
        } elseif ($user->isAdmin()) {
            // Admin can only see short URLs not created in their own company
            if ($user->company_id) {
                $query->where('company_id', '!=', $user->company_id);
            }
        } elseif ($user->isMember()) {
            // Member can only see short URLs not created by themselves
            $query->where('user_id', '!=', $user->id);
        } elseif ($user->isSales() || $user->isManager()) {
            // Sales and Manager can see all URLs from their company
            if ($user->company_id) {
                $query->where('company_id', $user->company_id);
            }
        }

        // Date filtering
        if ($request->has('date_filter')) {
            $dateFilter = $request->date_filter;
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                    break;
                case 'last_month':
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }

        $shortUrls = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('urls.index', compact('shortUrls'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Authorization: Admin, Member, and SuperAdmin cannot create short URLs
        // Only Sales and Manager can create short URLs
        if ($user->isAdmin() || $user->isMember() || $user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to create short URLs');
        }
        
        if (!$user->company_id) {
            return redirect()->back()->with('error', 'You must be associated with a company to create short URLs');
        }

        $request->validate([
            'long_url' => 'required|url',
        ]);

        $shortCode = Str::random(8);
        
        // Ensure uniqueness
        while (ShortUrl::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(8);
        }

        $shortUrl = ShortUrl::create([
            'short_code' => $shortCode,
            'long_url' => $request->long_url,
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ]);

        return redirect()->back()->with('success', 'Short URL created successfully!');
    }

    public function redirect($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();
        $shortUrl->incrementHits();
        return redirect($shortUrl->long_url);
    }

    public function download(Request $request)
    {
        $user = Auth::user();
        $query = ShortUrl::with(['user', 'company']);

        // Same authorization rules as index
        if ($user->isSuperAdmin()) {
            // SuperAdmin can only download filtered URLs
            if (!$request->has('date_filter') || empty($request->date_filter)) {
                return redirect()->back()->with('error', 'Please select a date filter to download URLs');
            }
        } elseif ($user->isAdmin()) {
            if ($user->company_id) {
                $query->where('company_id', '!=', $user->company_id);
            }
        } elseif ($user->isMember()) {
            $query->where('user_id', '!=', $user->id);
        } elseif ($user->isSales() || $user->isManager()) {
            if ($user->company_id) {
                $query->where('company_id', $user->company_id);
            }
        }

        // Date filtering
        if ($request->has('date_filter')) {
            $dateFilter = $request->date_filter;
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                    break;
                case 'last_month':
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }

        $shortUrls = $query->orderBy('created_at', 'desc')->get();

        $filename = 'short_urls_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($shortUrls) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Short URL', 'Long URL', 'Hits', 'Created On', 'User', 'Company']);
            
            foreach ($shortUrls as $url) {
                fputcsv($file, [
                    url('/s/' . $url->short_code),
                    $url->long_url,
                    $url->hits,
                    $url->created_at->format('d M Y'),
                    $url->user->name ?? 'N/A',
                    $url->company->name ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

