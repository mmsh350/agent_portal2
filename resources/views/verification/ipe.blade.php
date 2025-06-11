@extends('layouts.dashboard')

@section('title', 'NIN IPE Request')

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class=" grid-margin stretch-card col-md-12   grid-margin stretch-card ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">IPE Request</h4>
                        <p class="card-description">Send your ipe request to get your tracking number</p>
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
                                        <form name="ipe-form" id="ipe-form" method="POST"
                                            action="{{ route('user.ipe-request') }}">
                                            @csrf
                                            <div class="mb-3 row">

                                                <div class="col-md-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-md-12  mt-2 mb-0">
                                                            <p class="form-label">Tracking Number</p>
                                                            <input type="text" id="trackingId" name="trackingId"
                                                                maxlength="15" class="form-control" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-1 mb-2">

                                                <small class="text-danger">Our IPE request process is fully automated. You
                                                    can track the status of your request using the 'Check Status' button
                                                </small><br />

                                                <p class="fw-bold mt-2"> Service Fee:
                                                    &#x20A6;{{ number_format($ServiceFee->amount), 2 }}</p>

                                            </div>
                                            <button type="submit" id="submit" name="submit" class="btn btn-primary"><i
                                                    class="las la-share"></i> Submit
                                                Request
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!$ipes->isEmpty())
                                            @php
                                                $currentPage = $ipes->currentPage();
                                                $perPage = $ipes->perPage();
                                                $serialNumber = ($currentPage - 1) * $perPage + 1;
                                            @endphp
                                            <div class="table-responsive">
                                                <table class="table text-nowrap" style="background:#fafafc !important">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th width="5%" scope="col">ID</th>
                                                            <th scope="col">Request Tracking No.</th>
                                                            <th scope="col">Reply</th>
                                                            <th scope="col" class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i = 1; @endphp
                                                        @foreach ($ipes as $data)
                                                            <tr>
                                                                <th scope="row">{{ $serialNumber++ }}</th>
                                                                <td>{{ $data->trackingId }}</td>
                                                                <td>
                                                                    {{ $data->reply ?? 'No reply yet' }}
                                                                    <div>
                                                                        @if($data->reply && (!in_array($data->reply, ['Refunded', 'Failed', 'Blocked'])))
                                                                        <a href="{{ route('user.personalize-nin-auto', $data->reply) }}"
                                                                            class="text-primary text-decoration-none small">
                                                                            <i class="bi bi-pencil-square"></i> Personalize
                                                                        </a>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <td class="text-center">
                                                                    @if (is_null($data->reply))
                                                                        <a href="{{ route('user.ipeStatus', [$data->trackingId]) }}"
                                                                            class="btn btn-sm btn-primary">
                                                                            Check Status
                                                                        </a>
                                                                    @else
                                                                        Completed
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php $i++ @endphp
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                                <!-- Pagination Links -->
                                                <div class="d-flex justify-content-center">
                                                    {{ $ipes->links('vendor.pagination.bootstrap-4') }}
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ipe-form');
            const submitButton = document.getElementById('submit');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerText = 'Please wait while we process your request...';
            });
        });
    </script>
@endpush
