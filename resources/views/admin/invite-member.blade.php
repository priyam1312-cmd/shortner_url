@extends('layouts.app')

@section('title', 'Invite New Team Member')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">Invite New Team Member</h5>
        
        <form action="{{ route('admin.invite.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="User Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="ex. sample@example.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="role" class="form-label fw-semibold">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Sales">Sales</option>
                    <option value="Manager">Manager</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Send Invitation</button>
        </form>
    </div>
</div>
@endsection

