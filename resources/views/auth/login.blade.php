@extends('layouts.auth')
@section('title','Login')

@section('content')
<div class="auth-box">
    <div class="card card-outline card-primary shadow">
        <div class="card-header text-center">
            <h3 class="mb-0"><b>School Fees</b> App</h3>
            <p class="text-muted mb-0">Login to continue</p>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Enter email" required autofocus>
                    @error('email')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter password" required>
                    @error('password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember_me" name="remember">
                        <label for="remember_me">Remember Me</label>
                    </div>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </button>
            </form>

        </div>
    </div>

    <p class="text-center text-muted mt-3 mb-0">
        © {{ date('Y') }} School Fees Management
    </p>
</div>
@endsection
