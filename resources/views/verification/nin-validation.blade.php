@extends('layouts.dashboard')

@section('title', 'NIN Validation Request')

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class=" grid-margin stretch-card col-md-12   grid-margin stretch-card ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">NIN Validation Request</h4>
                        {{-- <p class="card-description">Send your nin validation request </p> --}}
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="new-tab" data-bs-toggle="tab" href="#new-1" role="tab"
                                    aria-controls="new-1" aria-selected="true">New</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="new-1" role="tabpanel" aria-labelledby="new-tab">

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
                                    <div class="col-md-4">
                                        <form name="validation-form" id="validation-form" method="POST"
                                            action="{{ route('user.nin-validation-request') }}">
                                            @csrf
                                            <div class="mb-3 row">

                                                <div class="col-md-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-md-12  mt-2 mb-0">
                                                            <p class="form-label">NIN Number</p>
                                                            <input type="text" id="nin_number" name="nin_number"
                                                                maxlength="11" class="form-control" required />
                                                        </div>
                                                        <div class="col-md-12  mt-2 mb-0">
                                                            <p class="form-label">Reason</p>
                                                            <textarea  id="description" name="description" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-1 mb-2">

                                                <small class="text-danger">N.B: VALIDATIONS TAKE UP TO 24-96HRS !
                                                </small><br />

                                                <p class="fw-bold mt-2"> Service Fee:
                                                    &#x20A6;{{ number_format($ServiceFee->amount), 2 }}</p>

                                            </div>
                                            <button type="submit" id="submit" name="submit" class="btn btn-primary"><i
                                                    class="las la-share"></i> Submit
                                                Request (₦ {{ $ServiceFee->amount}})
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!$validations->isEmpty())
                                            @php
                                                $currentPage = $validations->currentPage();
                                                $perPage = $validations->perPage();
                                                $serialNumber = ($currentPage - 1) * $perPage + 1;
                                            @endphp
                                            <div class="table-responsive">
                                                <table class="table text-nowrap" style="background:#fafafc !important">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th width="5%" scope="col">ID</th>
                                                            <th scope="col">Reference No.</th>
                                                            <th scope="col">NIN Number.</th>
                                                            <th scope="col" class="text-center">Status</th>
                                                            <th scope="col">Query</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i = 1; @endphp
                                                        @foreach ($validations as $data)
                                                            <tr>
                                                                <th scope="row">{{ $serialNumber++ }}</th>
                                                                <td>{{ $data->nin_number }}</td>
                                                                <td>{{ $data->refno }}</td>
                                                                <td class="text-center">
                                                                    @if ($data->status == 'resolved')
                                                                        <span
                                                                            class="btn btn-sm btn-primary">{{ Str::upper($data->status) }}</span>
                                                                    @elseif($data->status == 'rejected')
                                                                        <span class="btn btn-sm btn-danger">{{ Str::upper($data->status) }}</span>
                                                                    @elseif($data->status == 'pending')
                                                                        <span
                                                                            class="btn btn-sm btn-warning">{{ Str::upper($data->status) }}</span>
                                                                    @else
                                                                        <span
                                                                            class="btn btn-sm btn-secondary">{{ Str::upper($data->status) }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">

                                                                    <a type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-id="2"
                                                                    data-reason="{{ $data->reason }}"
                                                                    data-bs-target="#reason"
                                                                    class="btn btn-light btn-sm">
                                                                     <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i> <!-- Edit icon -->
                                                                 </a>

                                                                </td>

                                                            </tr>
                                                            @php $i++ @endphp
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                                <!-- Pagination Links -->
                                                <div class="d-flex justify-content-center">
                                                    {{ $validations->links('vendor.pagination.bootstrap-4') }}
                                                </div>
                                            </div>
                                        @else
                                            <center><img width="65%"
                                                    src="{{ asset('assets/images/no-transaction.gif') }}" alt="">
                                            </center>
                                            <p class="text-center fw-semibold  fs-15"> No Request
                                                Available!</p>
                                        @endif
                                    </div>

                                      <!-- Modals -->
                                    <div class="modal fade" id="reason" tabindex="-1" aria-labelledby="reason" data-bs-keyboard="true"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="staticBackdropLabel2">Support Query</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="message">No Message Yet.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        //Pay Modal
        $('#reason').on('shown.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var reason = button.data('reason')
            if (reason != '')
                $("#message").html(reason);
            else
                $("#message").html('No Message Yet.');
        });
    });
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('validation-form');
            const submitButton = document.getElementById('submit');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerText = 'Please wait while we process your request...';
            });
        });
    </script>
@endpush
