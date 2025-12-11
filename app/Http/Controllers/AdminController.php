<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function teamMembers()
    {
        $user = Auth::user();
        $members = User::where('company_id', $user->company_id)
            ->withCount('shortUrls')
            ->withSum('shortUrls', 'hits')
            ->paginate(10);

        return view('admin.team-members', compact('members'));
    }

    public function showInviteForm()
    {
        return view('admin.invite-member');
    }

    public function inviteMember(Request $request)
    {
        $user = Auth::user();
        
        // An Admin can't invite another Admin or Member in their own company
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Sales,Manager',
        ]);

        $token = Str::random(60);
        
        // Generate a temporary password
        $tempPassword = Str::random(12);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $user->company_id,
            'role' => $request->role,
            'password' => Hash::make($tempPassword),
            'temp_password' => $tempPassword, // Store plain password for display
            'invitation_token' => $token,
            'invited_at' => now(),
        ]);

        // In a real application, send invitation email here

        return redirect()->route('admin.team-members')->with('success', 'Team member invited successfully!');
    }
}

