@extends('layouts.dashboard')

@section('title', 'Verify NIN')
@push('styles')
<style>
    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 18px;
        z-index: 9999;
        flex-direction: column;
    }

    #overlay button {
        margin-top: 20px;
        padding: 10px 20px;
        background: #ff5252;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class="row">

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title m-0">
                                Verify NIN
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger alert-dismissible text-center" id="errorMsg"
                                style="display:none;" role="alert">
                                <small id="message">Processing your request.</small>
                            </div>
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <form id="verifyForm" name="verifyForm" method="POST">
                                        @csrf
                                        <div class="mb-3 row">
                                            <div class="col-md-12 mx-auto">
                                            </div>
                                            <div class="col-md-12 ">
                                                <p class="mb-2 text-muted">Verify NIN Number</p>
                                                <input type="text" id="nin" name="nin" value=""
                                                    class="form-control text-center" maxlength="11" required />
                                            </div>
                                            <div class="col-md-12 mx-auto">
                                            </div>
                                        </div>
                                        <button type="submit" id="verifyNIN" class="btn btn-primary"><i
                                                class="lar la-check-circle"></i> Check NIN Details (₦ {{$ServiceFee->amount}})</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 mt-2">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title m-0">
                                <i class="ri-user-search-line fw-bold"></i> Verified Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12  row">
                                <div class="alert alert-danger alert-dismissible text-center" id="errorMsg2"
                                    style="display:none;" role="alert">
                                    <small id="message2">Processing your request.</small>
                                </div>
                                <div class="validation-info col-md-12 mb-2 hidden" id="validation-info">
                                    <center>
                                        <img src="{{ asset('assets/images/search.png') }}" width="20%" alt="Search Icon">
                                        <p class="mt-5">This section will display search results </p>
                                    </center>
                                </div>

                                <div class="col-md-12">
                                    <div class="btn-list d-flex flex-column flex-md-row justify-content-center align-items-center gap-2 d-none"  id="download">
                                        <div>
                                            <a href="#" id="standardSlip" type="button"
                                                class="btn btn-primary btn-wave">
                                                <i class="bi bi-download"></i>&nbsp;
                                                Standard NIN Slip (&#x20A6;{{ $standard_nin_fee->amount }})
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" id="premiumSlip" type="button"
                                                class="btn btn-secondary btn-wave">
                                                <i class="bi bi-download"></i>&nbsp;
                                                Premium NIN Slip (&#x20A6;{{ $premium_nin_fee->amount }})
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" id="regularSlip" type="button"
                                                class="btn btn-info btn-wave">
                                                <i class="bi bi-download"></i>&nbsp;
                                                Regular NIN Slip (&#x20A6;{{ $regular_nin_fee->amount }})
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" id="basicSlip" type="button"
                                               class="btn btn-dark btn-wave w-100">
                                               <i class="bi bi-download"></i>&nbsp;
                                               Basic NIN Slip (&#x20A6;{{ $basic_nin_fee->amount }})
                                            </a>
                                         </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 mt-2">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title m-0">
                                <i class="ri-user-search-line fw-bold"></i> Verification History
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                @if (!$latestVerifications->isEmpty())
                                    @php
                                        $currentPage = $latestVerifications->currentPage();
                                        $perPage = $latestVerifications->perPage();
                                        $serialNumber = ($currentPage - 1) * $perPage + 1;
                                    @endphp

                                
                                    {{-- Desktop Table --}}
                                <div class="table-responsive d-none d-md-block">
                                    <table class="table table-hover align-middle text-nowrap" style="background:#fafafc">
                                        <thead class="thead-primary bg-primary text-white">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>NIN Number</th>
                                                <th>Tracking ID</th>
                                                <th>Status</th>
                                                <th>Download Slip</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($latestVerifications as $data)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
                                                    <td>{{ $data->idno }}</td>
                                                    <td>{{ $data->trackingId }}</td>
                                                    <td>
                                                        <span class="badge bg-success text-white">Success</span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                                                    id="dropdownMenuButton{{ $data->id }}"
                                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Choose Type
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $data->id }}">
                                                                <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Regular">
                                                                    <i class="bi bi-download me-1"></i> Regular (&#x20A6;{{ $regular_nin_fee->amount }})
                                                                </a>
                                                                <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Standard">
                                                                    <i class="bi bi-download me-1"></i> Standard (&#x20A6;{{ $standard_nin_fee->amount }})
                                                                </a>
                                                                <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Premium">
                                                                    <i class="bi bi-download me-1"></i> Premium (&#x20A6;{{ $premium_nin_fee->amount }})
                                                                </a>
                                                                <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Basic">
                                                                    <i class="bi bi-download me-1"></i> Basic (&#x20A6;{{ $basic_nin_fee->amount }})
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Mobile Cards --}}
                                <div class="d-block d-md-none">
                                    @foreach ($latestVerifications as $data)
                                        <div class="card mb-3 shadow-sm">
                                            <div class="card-body p-3">
                                                <h6 class="mb-2">NIN: <strong>{{ $data->idno }}</strong></h6>
                                                <p class="mb-1">Tracking ID: <strong>{{ $data->trackingId }}</strong></p>
                                                <p class="mb-2">Status: <span class="badge bg-success text-white">Success</span></p>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-primary btn-sm btn-block dropdown-toggle" type="button"
                                                            id="dropdownMenuMobile{{ $data->id }}"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Choose Download Type
                                                    </button>
                                                    <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuMobile{{ $data->id }}">
                                                        <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Regular">
                                                            <i class="bi bi-download me-1"></i> Regular (&#x20A6;{{ $regular_nin_fee->amount }})
                                                        </a>
                                                        <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Standard">
                                                            <i class="bi bi-download me-1"></i> Standard (&#x20A6;{{ $standard_nin_fee->amount }})
                                                        </a>
                                                        <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Premium">
                                                            <i class="bi bi-download me-1"></i> Premium (&#x20A6;{{ $premium_nin_fee->amount }})
                                                        </a>
                                                        <a class="dropdown-item dropdown-option" href="#" data-id="{{ $data->idno }}" data-value="Basic">
                                                            <i class="bi bi-download me-1"></i> Basic (&#x20A6;{{ $basic_nin_fee->amount }})
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                                    <!-- Pagination Links -->
                                    <div class="d-flex justify-content-center">
                                        {{ $latestVerifications->links('vendor.pagination.bootstrap-4') }}
                                    </div>
                                @else
                                    <div class="text-center">

                                        <p class="fw-semibold fs-5 mt-3">No Request Available!</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="overlay" style="display: none;">
        <div class="text-center">
            <p>Pop-ups are blocked. Please enable them to continue.</p>
            <button onclick="enablePopups()">Allow Pop-ups</button>
        </div>
    </div>
@endsection
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/nin.js') }}"></script>

    <script>
        function checkPopupStatus() {

            if (localStorage.getItem('popupsAllowed') === 'true') {
                document.getElementById('overlay').style.display = 'none';
                return true;
            }

            return false;
        }

        function testPopups() {
            try {
                const testPopup = window.open('', '_blank', 'width=1,height=1,left=-9999,top=-9999');
                if (testPopup && !testPopup.closed) {
                    testPopup.close();
                    localStorage.setItem('popupsAllowed', 'true');
                    return true;
                }
            } catch (e) {
                console.error("Popup test failed:", e);
            }
            return false;
        }


        function showOverlayIfBlocked() {
            if (checkPopupStatus()) return;

            if (!testPopups()) {
                document.getElementById('overlay').style.display = 'flex';
            } else {
                document.getElementById('overlay').style.display = 'none';
            }
        }


        function enablePopups() {
            if (testPopups()) {
                document.getElementById('overlay').style.display = 'none';
            } else {
                alert(
                    "Pop-ups are still blocked. Please:\n1. Click the popup blocker icon in your address bar\n2. Select 'Always allow pop-ups from this site'\n3. Refresh the page"
                );
            }
        }


        window.addEventListener('DOMContentLoaded', function() {

            if (!checkPopupStatus()) {

                setTimeout(showOverlayIfBlocked, 500);
            }
        });
    </script>
@endpush
