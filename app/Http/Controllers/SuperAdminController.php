<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isSuperAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function clients()
    {
        $companies = Company::with(['users' => function($query) {
            $query->orderBy('created_at', 'asc')->limit(1); // Get first user
        }])
        ->withCount(['users', 'shortUrls'])
        ->withSum('shortUrls', 'hits')
        ->paginate(10);

        return view('superadmin.clients', compact('companies'));
    }

    public function showInviteClientForm()
    {
        return view('superadmin.invite-client');
    }

    public function inviteClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'user_email' => 'required|email|unique:users,email',
            'user_name' => 'required|string|max:255',
            'role' => 'required|in:Sales,Manager', // SuperAdmin can't invite Admin
        ]);

        // SuperAdmin can't invite an Admin in a new company
        // So we create a company and invite a Sales/Manager
        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Create invitation token for the first user
        $token = Str::random(60);
        
        // Generate a temporary password
        $tempPassword = Str::random(12);
        
        // Create the user with invitation
        User::create([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'company_id' => $company->id,
            'role' => $request->role,
            'password' => Hash::make($tempPassword),
            'temp_password' => $tempPassword, // Store plain password for display
            'invitation_token' => $token,
            'invited_at' => now(),
        ]);

        // In a real application, send invitation email here

        return redirect()->route('superadmin.clients')->with('success', 'Client invitation sent successfully!');
    }
}

