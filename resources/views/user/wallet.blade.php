@extends('layouts.dashboard')

@section('title', 'Wallet')
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
                <div class="col-md-12">


                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Wallet Options</h4>

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="transaction-tab" data-bs-toggle="tab" href="#transaction"
                                        role="tab" aria-controls="transaction-1" aria-selected="true">Transactions</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="vwallet-tab" data-bs-toggle="tab" href="#vwallet" role="tab"
                                        aria-controls="vwallet-1" aria-selected="false" tabindex="-1">Payments Channel</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="claim-tab" data-bs-toggle="tab" href="#claim" role="tab"
                                        aria-controls="claim-1" aria-selected="false" tabindex="-1">Bonus</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="transaction" role="tabpanel"
                                    aria-labelledby="transaction-tab">
                                    <div class="media">
                                        <div class="table-responsive">
                                            @php
                                                $transactions = auth()->user()->transactions()->latest()->paginate(10);
                                                $serialNumber =
                                                    ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                                            @endphp

                                            @forelse ($transactions as $data)
                                                @if ($loop->first)
                                                    <table class="table text-nowrap"
                                                        style="background: #fafafc !important;">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th width="5%">ID</th>
                                                                <th>Reference No.</th>
                                                                <th>Service Type</th>
                                                                <th>Gate Way</th>
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
                                                    <td>{{ $data->gateway }}</td>
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
                                                        <a target="_blank"
                                                            href="{{ route('user.reciept', $data->referenceId) }}"
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
                                <div class="tab-pane fade" id="vwallet" role="tabpanel" aria-labelledby="vwallet-tab">
                                    <div class="media">
                                        <div class="media-body">
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
                                                                    <p class="account-name mb-1">{{ $data->accountName }}
                                                                    </p>
                                                                    <span
                                                                        class="account-number d-block">{{ $data->accountNo }}</span>
                                                                    <small
                                                                        class="bank-name text-muted">{{ $data->bankName }}</small>
                                                                </div>
                                                                <div class="copy-btn-wrap ms-auto">
                                                                    <button
                                                                        class="btn btn-outline-secondary btn-sm copy-account-number"
                                                                        data-account="{{ $data->accountNo }}">
                                                                        Copy
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="claim" role="tabpanel" aria-labelledby="claim-tab">
                                    @if (!$users->isEmpty())
                                        @php
                                            $currentPage = $users->currentPage(); // Current page number
                                            $perPage = $users->perPage(); // Number of items per page
                                            $serialNumber = ($currentPage - 1) * $perPage + 1; // Starting serial number for current page
                                        @endphp
                                        <div class="table-responsive" width="100%">
                                            <table class="table" style="background:#fafafc !important">
                                                <thead>
                                                    <tr class="table-primary ">
                                                        <th>ID</th>
                                                        <th>Email Address</th>
                                                        <th>Claim</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($users as $data)
                                                        <tr>
                                                            <th scope="row">{{ $serialNumber++ }}</th>
                                                            <td>{{ $data->email }}</td>
                                                            <td>
                                                                &#8358;
                                                                {{ number_format($data->total_bonus_amount, 2) }}
                                                            </td>
                                                            <td>
                                                                @if ($data->transactions_count >= $transaction_count && $data->claim_id == 0)
                                                                    <a href="{{ route('user.claim-bonus', $data->id) }}"
                                                                        class="btn btn-sm btn-success btn-wave waves-effect waves-light">
                                                                        <i
                                                                            class="ri-exchange-funds-line fs-16 align-middle me-1 d-inline-block"></i>Claim
                                                                    </a href>
                                                                @elseif ($data->claim_id == 1)
                                                                    <span class="badge bg-primary">Claimed</span>
                                                                @else
                                                                    <span class="badge bg-warning">Pending</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Links -->
                                            <div class="d-flex justify-content-center">
                                                {{ $users->links('vendor.pagination.bootstrap-4') }}
                                            </div>
                                        </div>
                                    @else
                                        <center><img width="65%" src="{{ asset('assets/images/no-transaction.gif') }}"
                                                alt=""></center>
                                        <p class="text-center fw-semibold  fs-15"> You have not referred any
                                            accounts yet. Invite friends and family to join and earn rewards
                                            when
                                            they complete the required number of transactions!</p>
                                    @endif
                                </div>
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
