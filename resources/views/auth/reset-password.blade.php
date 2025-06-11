@extends('layouts.auth')
@section('title', 'Reset Password')
@section('content')
    <div class="container  d-flex align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                        @else
                            <form action="{{ route('auth.password.update') }}" method="post">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <p class="mb-3">Please enter a new password to continue</p>

                                        @include('common.message') <!-- Include success/error messages -->

                                        <div class="form-group mb-3">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Password" required>

                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" placeholder="Confirm Password" required>

                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="row mt-4">
                                            <div class="d-flex gap-3 w-100">
                                                <button type="submit" class="btn btn-primary">Reset Password</button>
                                                <a href="{{ route('auth.login') }}" class="btn btn-secondary">Back to
                                                    Login</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
