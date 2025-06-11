<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
        <ul class="navbar-nav me-lg-2 d-none d-lg-flex">
            <li class="nav-item nav-toggler-item">
                <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
            </li>
            <li class="nav-item nav-search d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="search">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="search" aria-label="search"
                        aria-describedby="search">
                </div>
            </li>
        </ul>
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="{{ route('user.dashboard') }}"> <img
                    src="{{ asset('assets/images/' . $settings->logo ?? 'assets/images/logo.svg') }}"
                    alt="logo"></a>

            <a class="navbar-brand brand-logo-mini" href="{{ route('user.dashboard') }}">
                <img src="{{ asset('assets/images/' . $settings->mini_logo ?? 'assets/images/logo-mini.svg') }}"
                    alt="logo">
            </a>
        </div>
        <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item nav-profile dropdown">
                <a class="nav-link   d-block d-sm-none" href="#" data-bs-toggle="dropdown" id="profileDropdownSm">
                    @if (auth()->user()->profile_pic)
                        <img src="{{ 'data:image/;base64,' . auth()->user()->profile_pic }}" class="rounded-circle me-1"
                            alt="Profile Image" style="width: 32px; height: 32px; object-fit: cover;" />
                    @else
                        <i class="bi bi-person-circle" style="font-size: 2rem; color: #fff;"></i>
                    @endif
                    <span class="nav-profile-name">{{ auth()->user()->name }}</span>
                </a>

                <span class=" d-none d-sm-flex align-items-center" href="#" data-bs-toggle="dropdown"
                    id="profileDropdownLg">
                    <span class="nav-profile-name">Referral Code: <small
                            class="badge bg-danger">{{ ucwords(auth()->user()->referral_code) }}</small></span>
                </span>
            </li>
            <li class="nav-item nav-settings d-none d-lg-flex">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="mdi mdi-logout text-primary" title="Log out"></i>
                    </a>
                </form>
            </li>
            <li class="nav-item nav-toggler-item-right d-lg-none">
                <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </li>
        </ul>
    </div>

</nav>
