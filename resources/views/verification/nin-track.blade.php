@extends('layouts.dashboard')

@section('title', 'NIN Personalize')
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
                                Verify Tracking Number
                            </div>
                        </div>
                        <div class="card-body ">
                            {{-- <div class="alert alert-danger shadow-sm">
                                <center><svg class="d-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        width="36" height="36" fill="currentColor">
                                        <path
                                            d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM11 11V17H13V11H11ZM11 7V9H13V7H11Z">
                                        </path>
                                    </svg>
                                    <p> Note that &#x20A6;{{ $ServiceFee->amount }}. fee will be
                                        deducted
                                        from your wallet balance for each verification attempt, regardless
                                        of the outcome. This includes instances where the NIN is not
                                        successfully verified or if the data is not found.
                                    <p> Please confirm you have sufficient funds in your wallet before
                                        proceeding with the verification.
                                </center>
                            </div> --}}

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
                                                <p class="mb-2 text-muted">Tracking Number</p>
                                                <input type="text" id="nin" name="trackingId"
                                                    value="{{ $auto }}" class="form-control text-center"
                                                    maxlength="15" required />
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
                                <div class="validation-info  hidden col-md-12 mb-2" id="validation-info">
                                    <center>
                                        <img src="{{ asset('assets/images/search.png') }}" width="20%" alt="Search Icon">
                                        <p class="mt-5">This section will display search results </p>
                                    </center>
                                </div>
                                <div style="display:none;" id="downloadDiv">
                                    <div class="col-md-12">
                                        <div class="text-center d-none" id="download">
                                           <div class="row justify-content-center">
                                              <div class="col-12 col-sm-6 col-md-4 mb-2">
                                                 <a href="#" id="standardSlip" type="button"
                                                    class="btn btn-primary btn-wave w-100">
                                                    <i class="bi bi-download"></i>&nbsp;
                                                    Standard NIN Slip (&#x20A6;{{ $standard_nin_fee->amount }})
                                                 </a>
                                              </div>
                                              <div class="col-12 col-sm-6 col-md-4 mb-2">
                                                 <a href="#" id="premiumSlip" type="button"
                                                    class="btn btn-secondary btn-wave w-100">
                                                    <i class="bi bi-download"></i>&nbsp;
                                                    Premium NIN Slip (&#x20A6;{{ $premium_nin_fee->amount }})
                                                 </a>
                                              </div>
                                              <div class="col-12 col-sm-6 col-md-4 mb-2">
                                                 <a href="#" id="regularSlip" type="button"
                                                    class="btn btn-info btn-wave w-100">
                                                    <i class="bi bi-download"></i>&nbsp;
                                                    Regular NIN Slip (&#x20A6;{{ $regular_nin_fee->amount }})
                                                 </a>
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

    <div id="overlay" style="display: none;">
        <div class="text-center">
            <p>Pop-ups are blocked. Please enable them to continue.</p>
            <button onclick="enablePopups()">Allow Pop-ups</button>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/js/nin-track.js') }}"></script>
    <script>
        @if ($auto)
            $("#verifyNIN").trigger("click");
        @endif
    </script>

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
