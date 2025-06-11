@extends('layouts.dashboard')

@section('title', 'Show User')
@push('styles')
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
            <p class="mb-0">Here’s a quick look at your dashboard.</p>
        </div>

        @include('common.message')

        <div class="col-lg-12 grid-margin">
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3 border-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#profile"
                                        aria-selected="true">Profile</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#transaction"
                                        aria-selected="false">Transactions</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="profile" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 border-end text-center">
                                            <div class="mb-3 mt-3">
                                                @if ($user->profile_pic)
                                                    <img src="data:image/jpeg;base64,{{ $user->profile_pic }}"
                                                        class="rounded-circle shadow" alt="Profile Picture"
                                                        style="width: 200px; height: 200px;">
                                                @else
                                                    @php
                                                        $initials = collect(explode(' ', $user->name))
                                                            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                                            ->join('');
                                                    @endphp
                                                    <div class="d-flex justify-content-center align-items-center rounded-circle shadow bg-secondary text-white mx-auto"
                                                        style="width: 200px; height: 200px; font-size: 3rem;">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                            </div>
                                            <h5 class="mb-0">{{ $user->name }}</h5>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>

                                            @if ($user->referred_by && ($referrer = \App\Models\User::find($user->referred_by)))
                                                <div class="mt-4">
                                                    <h6 class="text-muted">Referred By:</h6>
                                                    <p class="mb-0">{{ $referrer->name }}</p>
                                                    <small class="text-muted">({{ $referrer->email }})</small>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-8 mt-2">
                                            <h4 class="mb-3">Account Details</h4>

                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Email</div>
                                                <div class="col-sm-8">{{ $user->email }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Phone Number</div>
                                                <div class="col-sm-8">{{ $user->phone_number }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Role</div>
                                                <div class="col-sm-8">{{ ucfirst($user->role) }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Wallet Balance</div>
                                                <div class="col-sm-8">
                                                    ₦{{ number_format(optional($user->wallet)->balance, 2) }}
                                                </div>
                                            </div>

                                            <hr>
                                            <h5 class="mb-3">Other Info</h5>

                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Wallet Created</div>
                                                <div class="col-sm-8">{{ $user->wallet_is_created ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Virtual Wallet</div>
                                                <div class="col-sm-8">{{ $user->vwallet_is_created ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Referral Code / Bonus</div>
                                                <div class="col-sm-8">{{ strtoupper($user->referral_code) }} /
                                                    {{ $user->referral_bonus }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Created At</div>
                                                <div class="col-sm-8">
                                                    {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 text-muted">Updated At</div>
                                                <div class="col-sm-8">
                                                    {{ $user->updated_at ? $user->updated_at->format('d/m/Y') : 'N/A' }}
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back
                                                    to Users</a>
                                                <a href="{{ route('admin.user.edit', $user) }}"
                                                    class="btn btn-primary">Edit User</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="transaction" role="tabpanel">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <p>Latest 20 Records</p>
                                            <small class="text-danger">Click on the reference number to generate a
                                                transaction receipt or use the download button</small>

                                            @if ($transactions->count())
                                                <table class="table text-nowrap" style="background:#fafafc !important">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>#</th>
                                                            <th>Reference No.</th>
                                                            <th>Service Type</th>
                                                            <th>Description</th>
                                                            <th>Amount</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Receipt</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transactions as $data)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <a target="_blank"
                                                                        href="{{ route('admin.reciept', $data->referenceId) }}">
                                                                        {{ strtoupper($data->referenceId) }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $data->service_type }}</td>
                                                                <td>{{ $data->service_description }}</td>
                                                                <td>&#8358;{{ number_format($data->amount, 2) }}</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="btn
                                                                        {{ $data->status == 'Approved' ? 'btn-success' : ($data->status == 'Rejected' ? 'btn-danger' : 'btn-warning') }}">
                                                                        {{ strtoupper($data->status) }}
                                                                    </span>
                                                                </td>

                                                                <td class="text-center">
                                                                    <a target="_blank"
                                                                        href="{{ route('admin.reciept', $data->referenceId) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="bi bi-download"></i> Download
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <center>
                                                    <img width="65%"
                                                        src="{{ asset('assets/images/no-transaction.gif') }}"
                                                        alt="">
                                                    <p class="text-center fw-semibold fs-15 mt-2">No Transaction Available!
                                                    </p>
                                                </center>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- tab content -->
                        </div>
                    </div>
                </div>
            </div>


        </div>

    @endsection
    @push('scripts')
    @endpush
