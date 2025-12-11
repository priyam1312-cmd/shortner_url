@extends('layouts.app')

@section('title', 'Generated Short URLs')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Generated Short URLs</h5>
        
        @if(auth()->user()->isSales() || auth()->user()->isManager())
        <div class="mb-4 p-3 bg-light rounded">
            <h6 class="mb-3 fw-semibold">Generate Short URL</h6>
            <form action="{{ route('urls.store') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="url" name="long_url" class="form-control" placeholder="e.g. https://sembark.com/travel-software/features/best-itinerary-builder" required>
                <button type="submit" class="btn btn-primary">Generate</button>
            </form>
        </div>
        @endif
        
        <div class="mb-4 p-3 bg-light rounded">
            <h6 class="mb-3 fw-semibold">View and Download based on Date Interval</h6>
            <div class="d-flex gap-2 align-items-end flex-wrap">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label mb-1">Date Filter</label>
                    <form action="{{ route('urls.index') }}" method="GET" id="dateFilterForm">
                        <select name="date_filter" class="form-select" onchange="document.getElementById('dateFilterForm').submit()">
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="last_week" {{ request('date_filter') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' || !request('date_filter') ? 'selected' : '' }}>This Month</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form action="{{ route('urls.download') }}" method="GET">
                        <input type="hidden" name="date_filter" value="{{ request('date_filter', 'this_month') }}">
                        <button type="submit" class="btn btn-primary">Download</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Short URL</th>
                        <th>Long URL</th>
                        <th>@if(auth()->user()->isAdmin())User @elseif(auth()->user()->isSuperAdmin())Company @else Hits @endif</th>
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shortUrls as $url)
                    <tr>
                        <td>
                            <a href="{{ url('/s/' . $url->short_code) }}" target="_blank" class="text-decoration-none">
                                {{ url('/s/' . $url->short_code) }}
                            </a>
                        </td>
                        <td>
                            <span class="text-muted" title="{{ $url->long_url }}">
                                {{ \Illuminate\Support\Str::limit($url->long_url, 60) }}
                            </span>
                        </td>
                        <td>
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-info">{{ $url->user->name ?? 'N/A' }}</span>
                            @elseif(auth()->user()->isSuperAdmin())
                                <span class="badge bg-warning text-dark">{{ $url->company->name ?? 'N/A' }}</span>
                            @else
                                <span class="badge bg-success">{{ $url->hits }}</span>
                            @endif
                        </td>
                        <td>{{ $url->created_at->format('d M \'y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No short URLs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $shortUrls->firstItem() ?? 0 }} of total {{ $shortUrls->total() }}
            </div>
            <div>
                {{ $shortUrls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

