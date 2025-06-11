@extends('layouts.dashboard')

@section('title', 'Dashboard')
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
        @if ($status == 'Pending')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                We're excited to have you on board! However, we need to verify your identity before activating your
                account. Simply click the link below to complete the verification process<br>
            </div>
        @endif
        @include('common.message')
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class="row">
                <div class="col-md-6 col-6 grid-margin stretch-card">
                    <div class="card hover-shadow">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="mdi mdi-wallet-outline mdi-36px"></i>
                                <p class="fw-medium mt-3">Main Wallet</p>
                            </div>
                            <h1 class="fw-light price">
                                ₦{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->balance, 2) : '0.00' }}
                            </h1>

                            <a href="#" data-bs-toggle="modal" data-bs-target="#walletModal"
                                class="btn btn-sm btn-outline-primary mt-3">
                                Add Fund
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-6 grid-margin stretch-card">
                    <div class="card hover-shadow">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="mdi mdi-gift-outline mdi-36px"></i>
                                <p class="fw-medium mt-3">Bonus Wallet</p>
                            </div>
                            <h1 class="fw-light price">
                                ₦{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->bonus, 2) : '0.00' }}
                            </h1>

                            <a href="{{ route('user.wallet') }}" class="btn btn-sm btn-outline-danger mt-3">
                                Claim Bonus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Left side column containing the icons -->
                <div class="col-lg-12 col-12 col-md-6">
                    <div class="container py-3" style="max-width: 100%">
                        <h4 class="fw-light mb-4 text-center">Our Services</h4>
                        <div class="row g-4">

                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">
                                            <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">
                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Verify NIN</h5>
                                        <a href="{{ route('user.verify-nin') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>


                            <!-- Service 1 -->
                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Verify NIN Phone</h5>
                                        <a href="{{ route('user.verify-nin-phone') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>

                              <!-- Service 1 -->
                              <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Verify NIN Demographic</h5>
                                        <a href="{{ route('user.verify-demo') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>


                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Instant IPE</h5>
                                        <a href="{{ route('user.ipe') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>


                            <!-- Service 2 -->
                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/bvn.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Verify BVN</h5>
                                        <a href="{{ route('user.verify-bvn') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>


                             <!-- Service 2 -->
                             <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/bvn.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">BVN By Phone Number</h5>
                                        <a href="{{ route('user.bvn-phone-search') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>



                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Personalize</h5>
                                        <a href="{{ route('user.personalize-nin') }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>


                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">Instant Validation</h5>
                                        <a href="#" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="icon-box mb-3">

                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="Logo"
                                                    style="max-width: 100px; max-height: 100px;">

                                        </div>
                                        <h5 class="icon-box-title mb-0 fw-bold">NIN Validation</h5>
                                        <a href="{{ route('user.nin-validation')}}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right side column for transaction table -->
                <div class="col-lg-12 stretch-card mt-">
                    <div class="container py-3" style="max-width: 100%">
                        <h4 class="fw-light mb-4 text-center">Recent Transactions</h4>
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="table-responsive">
                                    @php
                                        $transactions = auth()->user()->transactions()->latest()->paginate(10);
                                        $serialNumber =
                                            ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                                    @endphp

                                    @forelse ($transactions as $data)
                                        @if ($loop->first)
                                            <table class="table text-nowrap" style="background: #fafafc !important;">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th width="5%">ID</th>
                                                        <th>Reference No.</th>
                                                        <th>Service Type</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Receipt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                        @endif

                                        <tr>
                                            <td>{{ $serialNumber++ }}</td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('user.reciept', $data->referenceId) }}">
                                                    {{ strtoupper($data->referenceId) }}
                                                </a>
                                            </td>
                                            <td>{{ $data->service_type }}</td>
                                            <td>{{ $data->service_description }}</td>
                                            <td>&#8358;{{ number_format($data->amount, 2) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge
                                                    {{ $data->status == 'Approved' ? 'bg-success' : ($data->status == 'Rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ strtoupper($data->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a target="_blank" href="{{ route('user.reciept', $data->referenceId) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>

                                        @if ($loop->last)
                                            </tbody>
                                            </table>

                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $transactions->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center">
                                            <p class="fw-semibold fs-15 mt-2">No Transaction Available!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModal" data-bs-keyboard="true"
                data-bs-backdrop="static" data-bs-keyboard="false">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="staticBackdropLabel2">Verify Account
                            </h6>
                        </div>
                        <div class="modal-body">
                            We're excited to have you on board! However, we need to verify your identity before activating
                            your
                            account. provide your Identification number below.
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="col-md-6 col-lg-6">
                                <form id="verify" name="verifyForm" method="POST"
                                    action="{{ route('user.verify-user') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <p class="mb-2 text-muted text-center">Enter your BVN No.</p>
                                        <input type="text" id="bvn" name="bvn"
                                            class="form-control text-center" maxlength="11" required />
                                    </div>
                                    <div class="text-center mb-3 d-flex justify-content-center gap-2">
                                        <button type="submit" id="submit" class="btn btn-primary">
                                            <i class="lar la-check-circle"></i> Verify Now
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('logout') }}" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="las la-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="walletModalLabel">Fund Wallet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <small class="fw-semibold">Fund your wallet instantly by depositing
                                into the virtual account number</small>
                            <ul class="list-unstyled virtual-account-list mt-3 mb-0">
                                @if (auth()->user()->virtualAccount != null)
                                    @foreach (auth()->user()->virtualAccount as $data)
                                        <li class="account-item mb-3 p-2">
                                            <div class="d-flex align-items-start">
                                                <div class="bank-logo me-3">
                                                    <img width="80px" height="80px" style="object-fit: contain;"
                                                        src="{{ asset('assets/images/' . strtolower($data->bankName) . '.png') }}"
                                                        alt="{{ $data->bankName }} logo">
                                                </div>
                                                <div class="flex-fill">
                                                    <p class="account-name mb-1">{{ $data->accountName }}</p>
                                                    <span class="account-number d-block">{{ $data->accountNo }}</span>
                                                    <small class="bank-name text-muted">{{ $data->bankName }}</small>
                                                </div>
                                                <div class="copy-btn-wrap ms-auto">
                                                    <button class="btn btn-outline-secondary btn-sm copy-account-number"
                                                        data-account="{{ $data->accountNo }}">
                                                        Copy
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            <hr>
                            <center>
                                <a style="text-decoration:none" class="mb-2" href="{{ route('user.support') }}">
                                    <small class="fw-semibol text-danger">If your funds is not
                                        received within 30mins.
                                        Please Contact Support
                                        <i class="mdi mdi-headphones mdi-12px" style="font-size:24px"></i>
                                    </small> </a>

                                <a style="text-decoration:none" href="{{ route('user.wallet') }}">
                                    <h4 class="fw-semibol text-danger">Go to wallet
                                        <i class="mdi mdi-wallet-outline mdi-36px" style="font-size:24px"></i>
                                    </h4>
                                </a>

                                @if (!auth()->user()->vwallet_is_created)
                                    <a href="{{ route('user.verify-user') }}">Genearte Virtual accounts</a>
                                @endif
                            </center>

                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @push('scripts')
            <script>
                @if ($kycPending)
                    const kycModal = new bootstrap.Modal(document.getElementById('kycModal'));
                    kycModal.show();
                @endif

                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('verify');
                    const submitButton = document.getElementById('submit');

                    if (form && submitButton) {
                        form.addEventListener('submit', function() {
                            submitButton.disabled = true;
                            submitButton.innerText = 'Verifying ...';
                        });
                    }
                });


                document.querySelectorAll('.copy-account-number').forEach(button => {
                    button.addEventListener('click', function() {
                        const acctNo = this.getAttribute('data-account');
                        navigator.clipboard.writeText(acctNo);
                        this.innerText = 'Copied!';
                        setTimeout(() => {
                            this.innerText = 'Copy';
                        }, 2000);
                    });
                });
            </script>
        @endpush
