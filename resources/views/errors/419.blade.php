<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>419 | Session Expired</title>
  <link rel="shortcut icon"
  href="{{ asset('assets/images/' . $settings->favicon ?? 'assets/images/default_favicon.png') }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .error-box {
      max-width: 500px;
      padding: 40px;
      text-align: center;
      border-radius: 12px;
      background: white;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .error-icon {
      font-size: 4rem;
      color: #dc3545;
    }
    .btn-custom {
      border-radius: 25px;
      padding: 10px 30px;
      background:#082851;
      border: #082851;
    }
  </style>
</head>
<body>
    @php
    $timeout = config('session.lifetime');
    @endphp
  <div class="error-box">
    <div class="error-icon mb-3">
      <i class="bi bi-shield-exclamation"></i>
    </div>
    <h2 class="mb-3">Session Expired</h2>
    <p class="text-muted mb-4">
        Your session has expired due to {{ $timeout }} minute{{ $timeout > 1 ? 's' : '' }} of inactivity. Please log in again to continue.
      </p>
    <a href="{{ route('auth.login')}}" class="btn btn-danger btn-custom">
      <i class="bi bi-box-arrow-in-right mr-2"></i> Login Again
    </a>
  </div>

</body>
</html>
