<nav class="sidebar sidebar-offcanvas mt-0" id="sidebar">
    <!-- User Profile Section -->
    <div class="sidebar-profile text-center p-3">
        @if (auth()->user()->profile_pic)
            <img src="{{ 'data:image/jpeg;base64,' . auth()->user()->profile_pic }}" alt="Profile Picture"
                class="rounded-circle shadow" style="width: 80px; height: 80px; object-fit: cover;">
        @else
            <i class="bi bi-person-circle" style="font-size: 3rem; color: #fff;"></i>
        @endif

        <div class="sidebar-profile-info mt-2">
            <span class="sidebar-profile-name truncate-text">{{ auth()->user()->name }}</span>
            <span class="sidebar-profile-email text-light truncate-text">
                <small>{{ auth()->user()->email }}</small>
            </span>
        </div>
        <div class="d-block d-sm-none mt-1">
            <small>Referral Code:</small>
            <p class="badge bg-danger">{{ ucwords(auth()->user()->referral_code) }}</p>
        </div>
    </div>

    <ul class="nav">

        <!-- Dashboard Section -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.wallet') ? 'active' : '' }}" href="{{ route('user.wallet') }}">
                <i class="mdi mdi-wallet menu-icon"></i>
                <span class="menu-title">Fund Wallet</span>
            </a>
        </li>

        <!-- Verification Section -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.verify-nin') ? 'active' : '' }}"
                href="{{ route('user.verify-nin') }}">
                <i class="mdi mdi-fingerprint menu-icon"></i>
                <span class="menu-title">Verify NIN</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.verify-bvn') ? 'active' : '' }}"
                href="{{ route('user.verify-bvn') }}">
                <i class="mdi mdi-fingerprint menu-icon"></i>
                <span class="menu-title">Verify BVN</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.bvn-phone-search') ? 'active' : '' }}"
                href="{{ route('user.bvn-phone-search') }}">
                <i class="mdi mdi-magnify menu-icon"></i>
                <span class="menu-title">BVN Search</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.ipe') ? 'active' : '' }}" href="{{ route('user.ipe') }}">
                <i class="mdi mdi-sync menu-icon"></i>
                <span class="menu-title">Instant IPE</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.nin-validation') ? 'active' : '' }}"
                href="{{ route('user.nin-validation') }}">
                <i class="mdi mdi-sync menu-icon"></i>
                <span class="menu-title">NIN Validation</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.verify-nin-phone') ? 'active' : '' }}"
                href="{{ route('user.verify-nin-phone') }}">
                <i class="mdi mdi-phone menu-icon"></i>
                <span class="menu-title">Verify NIN PHONE</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.verify-demo') ? 'active' : '' }}"
                href="{{ route('user.verify-demo') }}">
                <i class="mdi mdi-account-group menu-icon"></i>
                <span class="menu-title">NIN Demographic</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.personalize-nin') ? 'active' : '' }}"
                href="{{ route('user.personalize-nin') }}">
                <i class="mdi mdi-magnify menu-icon"></i>
                <span class="menu-title">NIN Personalize</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.support') }}">
                <i class="mdi mdi-lifebuoy menu-icon"></i>
                <span class="menu-title">Support</span>
            </a>
        </li>
        <!-- Admin Section -->
        @if (in_array(auth()->user()->role, ['admin', 'super admin']))
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleSubmenu(event, 'adminSubmenu')">
                    <i class="mdi mdi-cog-outline menu-icon"></i>
                    <span class="menu-title">Manage</span>
                    <i class="mdi mdi-chevron-down ms-auto"></i>
                </a>
                <ul class="sub-menu nav flex-column ps-4" id="adminSubmenu">

                    @if (in_array(auth()->user()->role, ['super admin']))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.services.index') ? 'active' : '' }}"
                                href="{{ route('admin.services.index') }}">
                                <i class="mdi mdi-pencil menu-icon"></i> Services
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.users.index') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="mdi mdi mdi-account-multiple menu-icon"></i> Users
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.nin.services.list') ? 'active' : '' }}"
                            href="{{ route('admin.nin.services.list') }}">
                            <i class="mdi mdi-tools menu-icon"></i>NIN Services
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.bvn.services.list') ? 'active' : '' }}"
                            href="{{ route('admin.bvn.services.list') }}">
                            <i class="mdi mdi-tools menu-icon"></i>BVN Services
                        </a>
                    </li>
                </ul>

            </li>
        @endif
        <!-- Logout Section -->
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <a class="nav-link d-flex align-items-center" style="margin-left:14px;" href="#"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </a>
            </form>
        </li>
    </ul>
</nav>
