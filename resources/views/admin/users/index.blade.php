@extends('layouts.dashboard')

@section('title', 'Users')
@push('styles')
    <style>
        /* Default style (for larger screens) */
        .price {
            font-size: 2rem;
            /* Default font size for larger screens */
            white-space: normal;
            /* Allow wrapping on larger screens */
            overflow: visible;
            /* Allow content to overflow if necessary */
            text-overflow: unset;
            /* Reset ellipsis */
            line-height: 1.2;
            /* Standard line height */
        }

        /* Style for smaller screens (e.g., mobile or tablet) */
        @media (max-width: 767px) {
            .price {
                font-size: 1.2rem;
                /* Adjust font size for smaller screens */
                white-space: nowrap;
                /* Prevent text from wrapping */
                overflow: hidden;
                /* Hide overflow */
                text-overflow: ellipsis;
                /* Show ellipsis if text overflows */
            }
        }

        /* General Styles for Service Cards */
        .service-card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .icon-box {
            margin-bottom: 1.5rem;
        }

        .icon-box-media {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #5e2572;
            border-radius: 50%;
            width: 70px;
            height: 70px;
        }

        .icon-box-title {
            font-weight: bolder;
            font-size: 1rem;
            color: #333;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .icon-box-media {
                width: 60px;
                height: 60px;
            }

            .icon-box-title {
                font-size: 1rem;
            }
        }

        /* Ensures 2 items per row on mobile (smaller than 576px) */
        @media (max-width: 576px) {
            .col-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .icon-box-media {
                width: 50px;
                height: 50px;
            }

            .icon-box-title {
                font-size: 0.9rem;
            }
        }

        /* Custom CSS for icon box */
        .icon-box-media {
            transition: transform 0.3s ease;
        }

        .icon-box-media:hover {
            transform: scale(1.1);
        }

        /* Custom CSS for cards */
        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .copy-btn-wrap .btn {
            padding: 4px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background-color: #007bff;
            /* Bootstrap primary blue */
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .copy-btn-wrap .btn:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
            <p class="mb-0">Here’s a quick look at your dashboard.</p>
        </div>

        <div class="col-lg-12 grid-margin">

            <div class="row g-4">
                <!-- Total Users -->
                <div class="col-md-4 col-12">
                    <div class="card hover-shadow h-100 border-0">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-account-group-outline text-primary mdi-36px"></i>
                                <p class="fw-semibold mt-2 text-muted">Total Users</p>
                            </div>
                            <h2 class="fw-bold text-dark">
                                {{ $allUsers }}
                            </h2>
                            <span class="badge bg-secondary">All Accounts</span>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="col-md-4 col-12">
                    <div class="card hover-shadow h-100 border-0">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-account-check-outline text-success mdi-36px"></i>
                                <p class="fw-semibold mt-2 text-muted">Active Users</p>
                            </div>
                            <h2 class="fw-bold text-dark">
                                {{ $active }}
                            </h2>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Inactive Users -->
                <div class="col-md-4 col-12">
                    <div class="card hover-shadow h-100 border-0">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-account-off-outline text-danger mdi-36px"></i>
                                <p class="fw-semibold mt-2 text-muted">Inactive Users</p>
                            </div>
                            <h2 class="fw-bold text-dark">
                                {{ $notActive }}
                            </h2>
                            <span class="badge bg-danger">Inactive</span>
                        </div>
                    </div>
                </div>
            </div>

            <!--  / Users -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold text-center mb-4">Users List</h4>

                            <form method="GET" class="row g-2 mb-4 justify-content-center">
                                <div class="col-md-4">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search..." class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="per_page" value="{{ request('per_page', 10) }}"
                                        class="form-control" placeholder="Per page">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>


                            @include('common.message')
                            <div class="table-responsive">
                                <table class="table table-sm  " style="background: #fafafc;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Active</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    {!! $user->name
                                                        ? e(trim("{$user->name}"))
                                                        : '<i class="bx bx-info-circle text-muted" title="Name missing"> Missing</i>' !!}
                                                </td>
                                                <td>
                                                    {!! $user->phone_number
                                                        ? e($user->phone_number)
                                                        : '<i class="bx bx-info-circle text-muted" title="Phone no missing"> Missing</i>' !!}
                                                </td>
                                                <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
                                                <td>{{ ucwords($user->role) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.user.show', $user) }}"
                                                        class="btn btn-info btn-sm mb-1">
                                                        <i class="bx bx-show"></i> View
                                                    </a>
                                                    <a href="{{ route('admin.user.edit', $user) }}"
                                                        class="btn btn-warning btn-sm mb-1">
                                                        <i class="bx bx-edit"></i> Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.user.activate', $user) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                                            <i
                                                                class="bx {{ $user->is_active ? 'bx-user-x' : 'bx-user-check' }}"></i>
                                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">No users found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center mt-4">
                                    {{ $users->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    @endsection
    @push('scripts')
    @endpush
