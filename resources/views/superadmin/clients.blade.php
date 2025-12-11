@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Clients</h5>
            <a href="{{ route('superadmin.invite-client') }}" class="btn btn-primary">Invite</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Users</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr>
                        <td><strong>{{ $company->name }}</strong></td>
                        <td>{{ $company->email }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $company->users_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $company->short_urls_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $company->short_urls_sum_hits ?? 0 }}</span>
                        </td>
                        <td>
                            @if($company->users->first() && $company->users->first()->temp_password)
                                <code class="bg-light text-dark px-2 py-1 rounded" style="font-size: 0.9em;">{{ $company->users->first()->temp_password }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No clients found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $companies->firstItem() ?? 0 }} of total {{ $companies->total() }}
            </div>
            <div>
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

