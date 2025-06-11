<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', $settings->site_name ?? config('app.name'))</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.base.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <link rel="shortcut icon"
        href="{{ asset('assets/images/' . $settings->favicon ?? 'assets/images/default_favicon.png') }}">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- endinject -->
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        /* Sidebar base styling */
        .sidebar {
            background-color: #082851;
            /* Your brand navy */
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            /* Matching your main font */
            padding-top: 7px;
            padding-bottom: 10px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            /* Subtle divider */
            margin: 0 10px;
        }

        .sidebar .nav-link {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 4px;
            margin: 2px 0;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
            border-left: 3px solid #ffffff;
        }

        .sidebar .nav .nav-item .nav-link .menu-title {
            color: inherit;
        }

        .sidebar .menu-icon {
            margin-right: 12px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .menu-title {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Sub-menu styling */
        .sidebar .sub-menu {
            display: none;
            padding-left: 1.5rem;
            background-color: rgba(8, 40, 81, 0.8);
            /* Darker navy */
            border-left: 2px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .sub-menu.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .sidebar .sub-menu .nav-link {
            padding: 12px 20px;
            font-size: 14px;
            text-transform: none;
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .sub-menu .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Profile section */
        .sidebar-profile {
            background-color: rgba(0, 0, 0, 0.15);
            padding: 15px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-profile-info {
            color: #ffffff;
        }

        .sidebar-profile-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-profile-email small {
            opacity: 0.8;
            font-size: 0.85rem;
        }

        /* Collapsed state */
        .sidebar.collapsed .sidebar-profile-info,
        .sidebar.collapsed .sidebar-profile {
            display: none !important;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 15px 10px;
        }

        .sidebar.collapsed .menu-title {
            display: none;
        }

        .sidebar.collapsed .menu-icon {
            margin-right: 0;
            font-size: 20px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                padding-top: 5px;
            }

            .sidebar .nav-link {
                padding: 12px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="page-loading" id="loader">
        <div class="page-loading-inner">



            <div class="loader-demo-box mb-5" style="height:0px; border:0px !important;">
                <div class="circle-loader"></div>
            </div>


            <h6 class="loader-text">
                {{ $settings->short_name ?? config('app.name') }}
            </h6>

        </div>

    </div>
    <div class="container-scroller">
        @include('partials.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('partials.sidebar')

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    @yield('content')

                </div>
                <!-- content-wrapper ends -->

                @include('partials.footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('assets/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function toggleSubmenu(e, id) {
            e.preventDefault();
            const submenu = document.getElementById(id);
            submenu.classList.toggle('show');
        }
    </script>
    <!-- endinject -->

    <!-- Custom js for this page-->

    @stack('scripts')

</body>

</html>
