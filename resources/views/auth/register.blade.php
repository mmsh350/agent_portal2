@extends('layouts.auth')

@section('title', 'Register')
@push('styles')
    <style>
        .form-check {
            display: flex;
        }

        .form-check .form-check-label {
            margin-left: 0.85rem;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="auth-form-transparent text-start p-3">
                    {{-- <div class="brand-logo">
                        <img src="{{ asset('assets/images/' . $settings->logo ?? 'assets/images/logo.svg') }}" alt="logo">
                    </div> --}}
                    <h4>New here?</h4>
                    <h6 class="fw-light">Join us today! It takes only a few steps</h6>

                    @include('common.message')

                    <form method="POST" action="{{ route('auth.register') }}" class="pt-3 needs-validation" novalidate>
                        @csrf

                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-account-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="text"
                                    class="form-control form-control-lg border-left-0 @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Full Name" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-phone-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="tel"
                                    class="form-control form-control-lg border-left-0 @error('phone_number') is-invalid @enderror"
                                    id="phone_number" name="phone_number" maxlength="11" placeholder="Phone Number"
                                    value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-email-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="email"
                                    class="form-control form-control-lg border-left-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Referral Code (Invitation Code) -->
                        <div class="form-group">
                            <label for="referral_code">Referral Code (Optional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-tag-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="text"
                                    class="form-control form-control-lg border-left-0 @error('referral_code') is-invalid @enderror"
                                    id="referral_code" name="referral_code" maxlength="6"
                                    placeholder="Referral Code (if any)" value="{{ old('referral_code') }}">
                                @error('referral_code')
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

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-lock-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control form-control-lg border-left-0"
                                    id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                                    required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check flex">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" name="terms" id="terms" value="1"
                                        class="form-check-input @error('terms') is-invalid @enderror"{{ old('terms') ? 'checked' : '' }}>

                                </label>
                                <label class="form-check-label text-muted">
                                    I agree to all <a href="#">Terms & Conditions</a>
                                </label>
                            </div>
                            @error('terms')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-3 d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-medium auth-form-btn">
                                SIGN UP
                            </button>
                        </div>

                        <div class="text-center mt-4 fw-light">
                            Already have an account? <a href="{{ route('auth.login') }}" class="text-primary">Login</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right side (Image or background) -->
            <div class="col-lg-6 register-half-bg d-none d-lg-flex flex-row"
                style="background: url('{{ asset('assets/images/' . ($settings->registration_background_image ?? 'register-bg.jpg')) }}') no-repeat center center !important; background-size: cover !important;">
                <p class="text-white fw-medium text-center flex-grow align-self-end">Copyright &copy; {{ date('Y') }}
                    All rights reserved.</p>
            </div>
        </div>
    </div>
@endsection
