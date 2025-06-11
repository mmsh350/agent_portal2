@extends('layouts.email')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>
                Dear {{ $user }}
            </h4>
            <p>
                We received a request to reset your password for your account. If you didn't
                request a password reset, you can safely ignore this email.

                To reset your password, please click the link below:
            </p>

            <a href="{{ $link }}">{{ $link }}</a>

            <p>
                This link will expire in 30 minutes. If you need any assistance, feel free to reach out to our support
                team.
            </p>
        </div>
    </div>
@endsection
