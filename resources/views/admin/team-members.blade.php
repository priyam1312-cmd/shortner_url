@extends('layouts.app')

@section('title', 'Team Members')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Team Members</h5>
            <a href="{{ route('admin.invite') }}" class="btn btn-primary">Invite</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td><strong>{{ $member->name }}</strong></td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $member->role }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $member->short_urls_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $member->short_urls_sum_hits ?? 0 }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No team members found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $members->firstItem() ?? 0 }} of total {{ $members->total() }}
            </div>
            <div>
                {{ $members->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

