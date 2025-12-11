<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.clients');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.urls.index');
        } elseif ($user->isMember()) {
            return redirect()->route('member.urls.index');
        } elseif ($user->isSales() || $user->isManager()) {
            return redirect()->route('urls.index');
        }
        
        return redirect()->route('urls.index');
    }
}

