<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URL Shortner - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .header {
            padding: 1.25rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header.member-header {
            background-color: #28a745;
            color: white;
        }
        .header.admin-header {
            background-color: #007bff;
            color: white;
        }
        .header.superadmin-header {
            background-color: #ffc107;
            color: #333;
        }
        .header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .header-nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        .header-nav a {
            color: inherit;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .header-nav a:hover {
            opacity: 0.8;
        }
        .logout-btn {
            background: none;
            border: none;
            color: inherit;
            text-decoration: none;
            font-weight: 500;
            padding: 0;
            cursor: pointer;
        }
        .logout-btn:hover {
            opacity: 0.8;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-radius: 8px;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table {
            background-color: #fff;
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .form-control, .form-select {
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .pagination {
            margin-bottom: 0;
        }
        .pagination .page-link {
            color: #007bff;
            border-color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .alert {
            border-radius: 6px;
            border: none;
        }
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $headerClass = 'header';
        $headerTitle = 'Dashboard';
        
        if ($user->isSuperAdmin()) {
            $headerClass .= ' superadmin-header';
            $headerTitle = 'Super Admin Dashboard';
        } elseif ($user->isAdmin()) {
            $headerClass .= ' admin-header';
            $headerTitle = 'Client Admin Dashboard';
        } elseif ($user->isMember()) {
            $headerClass .= ' member-header';
            $headerTitle = 'Client Member Dashboard';
        } else {
            $headerClass .= ' admin-header';
            $headerTitle = 'Dashboard';
        }
    @endphp
    
    <div class="{{ $headerClass }} d-flex justify-content-between align-items-center">
        <h3>{{ $headerTitle }}</h3>
        <div class="header-nav">
            <a href="{{ route('dashboard') }}">>URL< Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn">Logout →</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

