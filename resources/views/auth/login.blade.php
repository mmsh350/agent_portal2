@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="auth-form-transparent text-start p-3">
                    <div class="brand-logo">
                        <a href="{{ url('/') }}"> <img
                                src="{{ asset('assets/images/' . $settings->logo ?? 'assets/images/logo.svg') }}"
                                alt="logo"></a>
                    </div>
                    <h4>Welcome back!</h4>
                    <h6 class="fw-light">Happy to see you again!</h6>

                    @include('common.message')

                    <form method="POST" action="{{ route('auth.login') }}" class="pt-3 needs-validation" novalidate>
                        @csrf

                        <!-- Username -->
                        <div class="form-group">
                            <label for="email">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-account-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="text"
                                    class="form-control form-control-lg border-left-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Username" value="{{ old('email') }}"
                                    required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-lock-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="password"
                                    class="form-control form-control-lg border-left-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Password" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    Keep me signed in
                                </label>
                            </div>
                            <a href="{{ route('auth.password.request') }}" class="auth-link text-black">Forgot password?</a>
                        </div>
                        <!-- Submit Button -->
                        <div class="my-3 d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-medium auth-form-btn">
                                LOGIN
                            </button>
                        </div>

                        <div class="text-center mt-4 fw-light">
                            Don't have an account? <a href="{{ route('auth.register') }}" class="text-primary">Create</a>
                        </div>
                    </form>
                    <p class="text-center mt-2"> <a href="/">Go to Back</a></p>
                </div>
            </div>

            <!-- Right side (Image or background) -->
            <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row"
                style="background: url('{{ asset('assets/images/' . ($settings->login_background_image ?? 'login-bg.jpg')) }}') no-repeat center center !important; background-size: cover !important;">
                <p class="text-white fw-medium text-center flex-grow align-self-end">Copyright &copy; {{ date('Y') }}
                    All rights reserved.</p>
            </div>
        </div>
    </div>

@endsection
