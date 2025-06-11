<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap Css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header id="page-topbar" class="p-2">
        <div class="navbar-header">
            <div class="text-center justify-content-center">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ url('/') }}" class="logo logo-light" target="_blank">
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/' . $settings->logo ?? 'assets/images/logo.svg') }}"
                                alt="" height="50">
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        @yield('content')
    </div>


    <div class="row mt-2 card col-md-6 m-auto p-4 rounded text-center">
        Sincerely,
        <br>
        {{ $settings->site_name ?? config('app.name') }}
        <br>
        <a href="/"> {{ $settings->short_name ?? config('app.name') }}</a>
    </div>

</body>

</html>
