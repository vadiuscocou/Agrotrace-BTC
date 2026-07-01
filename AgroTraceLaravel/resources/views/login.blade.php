@extends('layouts.app')
@section('title', 'Login - AgroTrace-BTC')
@section('content')
<div class="container py-5 text-center">
    <h2>Hackathon MVP Login</h2>
    <p class="text-muted mb-5">Select a role to login as (Simulated Authentication)</p>
    
    <div class="row justify-content-center gap-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light p-4">
                <h4>Investor</h4>
                <p class="small text-muted">Diaspora Investor testing the Lightning investment flow.</p>
                <!-- Assuming investor is user ID 2 based on Seeder -->
                <a href="{{ url('/login-as/2') }}" class="btn btn-warning w-100">Login as Investor</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light p-4">
                <h4>Project Owner</h4>
                <p class="small text-muted">Cooperative managing milestones and subscriptions.</p>
                <!-- Assuming owner is user ID 3 based on Seeder -->
                <a href="{{ url('/login-as/3') }}" class="btn btn-success w-100">Login as Owner</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-dark text-white p-4">
                <h4>Admin</h4>
                <p class="small text-muted">Platform admin validating projects and proofs.</p>
                <!-- Assuming admin is user ID 1 based on Seeder -->
                <a href="{{ url('/login-as/1') }}" class="btn btn-primary w-100">Login as Admin</a>
            </div>
        </div>
    </div>
</div>
@endsection
