@extends('layouts.dashboard')

@section('title', 'BVN User Request')

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class=" grid-margin stretch-card col-md-10   grid-margin stretch-card ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">BVN User Request</h4>
                        <p class="card-description">Apply for BVN User Request: Become
                            an
                            Authorized Agent for BVN Support</p>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="new-tab" data-bs-toggle="tab" href="#new-1" role="tab"
                                    aria-controls="new-1" aria-selected="true">New</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history-1" role="tab"
                                    aria-controls="history-1" aria-selected="false" tabindex="-1">Request History</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="new-1" role="tabpanel" aria-labelledby="new-tab">

                                <center>
                                    <img class="img-fluid" src="{{ asset('assets/images/bvn.jpg') }}" width="30%">
                                </center>
                                <center>
                                    <small class="font-italic text-danger"><i>Please note that this
                                            request will be processed in the next 5 Working days. Kindly
                                            provide a valid email address and phone nummber.
                                            In addition the email address and phone number provided should be unique to
                                            this user
                                            and not already associated with another registered user.
                                        </i>
                                    </small>
                                </center>
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row text-center">
                                    <div class="col-md-12">
                                        <form name="enroll" id="enroll" method="POST"
                                            action="{{ route('user.enroll-bvn') }}">
                                            @csrf
                                            <div class="mb-3 row">


                                                <div class="col-md-12  mt-2 mb-0">
                                                    <p class="form-label">Fullname</p>
                                                    <input type="text" id="fullname" name="fullname"
                                                        class="form-control  " required />
                                                </div>
                                                <div class="col-md-12  mt-2 mb-0">
                                                    <p class="form-label">Email Address</p>
                                                    <input type="text" id="email" name="email"
                                                        class="form-control  " required />
                                                </div>
                                                <div class="col-md-12  mt-2 mb-0">
                                                    <p class="form-label">Phone Number</p>
                                                    <input type="text" id="phone" name="phone" maxlength="11"
                                                        class="form-control  " required />
                                                </div>

                                                <div class="col-md-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-md-6 mt-2 mb-0">
                                                            <p class="form-label">Username</p>
                                                            <input type="text" id="username" name="username"
                                                                class="form-control" />
                                                        </div>

                                                        <div class="col-md-6  mt-2 mb-0">
                                                            <p class="form-label">State</p>
                                                            <input type="text" id="state" name="state"
                                                                class="form-control  " required />
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-0">
                                                            <p class="form-label">City</p>
                                                            <input type="text" id="city" name="city"
                                                                class="form-control  " required />
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-0">
                                                            <p class="form-label">LGA </p>
                                                            <input type="text" id="lga" name="lga"
                                                                class="form-control  " required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12  mt-2 mb-0">
                                                    <p class="form-label">Business Address </p>
                                                    <textarea class="form-control" name="address" id="address" required></textarea>
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-md-6  mt-2 mb-0">
                                                            <p class="form-label">BVN</p>
                                                            <input type="text" id="bvn" name="bvn"
                                                                maxlength="11" class="form-control" required />
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-0">
                                                            <p class="form-label">Account Number </p>
                                                            <input type="text" id="account_number"
                                                                name="account_number" maxlength="10"
                                                                class="form-control  " required />
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-0">
                                                            <p class="form-label">Bank Name </p>
                                                            <input type="text" id="bank_name" name="bank_name"
                                                                class="form-control  " required />
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-0">
                                                            <p class="form-label">Account Name </p>
                                                            <input type="text" id="account_name" name="account_name"
                                                                class="form-control  " required />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-1 mb-2">
                                                <h6>* Key Notes:</h6>
                                                <small class="text-danger">Account Most be traditional bank
                                                    account, not a fintech or digital banking account
                                                </small><br />
                                                <small class="text-danger fw-bold">Andriod Access only </small>
                                                <p class="fw-bold"> Enrollment Fee:
                                                    &#x20A6;{{ number_format($ServiceFee->amount), 2 }}</p>

                                            </div>
                                            <button type="submit" id="submit" name="submit"
                                                class="btn btn-primary"><i class="las la-share"></i> Submit
                                                Request</button>
                                        </form>
                                    </div>

                                </div>

                            </div>
                            <div class="tab-pane fade" id="history-1" role="tabpanel" aria-labelledby="history-tab">


                                @if (!$enrollments->isEmpty())
                                    @php
                                        $currentPage = $enrollments->currentPage(); // Current page number
                                        $perPage = $enrollments->perPage(); // Number of items per page
                                        $serialNumber = ($currentPage - 1) * $perPage + 1; // Starting serial number for current page
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="table text-nowrap" style="background:#fafafc !important">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th width="5%" scope="col">ID</th>
                                                    <th scope="col">Reference No.</th>
                                                    <th scope="col">Fullname</th>
                                                    <th scope="col" class="text-center">Status
                                                    </th>
                                                    <th scope="col" class="text-center">Response</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($enrollments as $data)
                                                    <tr>
                                                        <th scope="row">{{ $serialNumber++ }}</th>
                                                        <td>{{ $data->refno }}</td>
                                                        <td>{{ $data->fullname }}</td>
                                                        <td class="text-center">

                                                            @if ($data->status == 'successful')
                                                                <span
                                                                    class="badge bg-success">{{ Str::upper($data->status) }}</span>
                                                            @elseif($data->status == 'rejected')
                                                                <span
                                                                    class="badge bg-danger">{{ Str::upper($data->status) }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-warning">{{ Str::upper($data->status) }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a type="button" data-bs-toggle="modal" data-id="2"
                                                                data-reason="{{ $data->reason }}"
                                                                data-bs-target="#reason">

                                                                <i class="ti-info-alt" style="font-size:24px"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @php $i++ @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination Links -->
                                        <div class="d-flex justify-content-center">
                                            {{ $enrollments->links('vendor.pagination.bootstrap-4') }}
                                        </div>
                                    </div>
                                @else
                                    <center><img width="65%" src="{{ asset('assets/images/no-transaction.gif') }}"
                                            alt=""></center>
                                    <p class="text-center fw-semibold  fs-15"> No Request
                                        Available!</p>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="reason" tabindex="-1" aria-labelledby="reason" data-bs-keyboard="true"
                aria-hidden="true">
                <!-- Scrollable modal -->
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="staticBackdropLabel2">Support
                            </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="message">No Message Yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('enroll');
            const submitButton = document.getElementById('submit');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerText = 'Please wait while we process your request...';
            });
        });

        $("#reason").on("shown.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var reason = button.data("reason");
            if (reason != "") $("#message").html(reason);
            else $("#message").html("No Message Yet.");
        });
    </script>
@endpush
