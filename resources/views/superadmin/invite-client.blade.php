@extends('layouts.app')

@section('title', 'Invite New Client')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">Invite New Client</h5>
        
        <form action="{{ route('superadmin.invite-client.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <h6 class="mb-3 fw-semibold">Client Information</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold">Client Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Client Name..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Client Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="ex. sample@example.com" required>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="mb-4">
                <h6 class="mb-3 fw-semibold">First User Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_name" class="form-label fw-semibold">User Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="user_email" class="form-label fw-semibold">User Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" placeholder="ex. sample@example.com" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="Sales">Sales</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Send Invitation</button>
        </form>
    </div>
</div>
@endsection

