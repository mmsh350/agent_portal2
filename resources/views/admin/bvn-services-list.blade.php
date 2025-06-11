@extends('layouts.dashboard')

@section('title', 'BVN Phone Search')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .form-check .form-check-input {
            margin-left: 0;
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
                    <div class="card custom-card ">
                        <div class="card-header">
                            <h5 class="card-title">BVN Search Request</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {!! session('success') !!}
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

                            <div class="col-xl-12 mb-3">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span
                                                            class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                                            <i class="las la-tasks"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">All Request</p>
                                                                <h4 class="fw-semibold mt-1">{{ $total_request }}</h4>
                                                            </div>
                                                            <div id="crm-total-customers"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span
                                                            class="avatar avatar-md avatar-rounded bg-success-transparent">
                                                            <i class="las la-check-double"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">Resolved</p>
                                                                <h4 class="fw-semibold mt-1">{{ $resolved }}</h4>
                                                            </div>
                                                            <div id="crm-total-deals"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span
                                                            class="avatar avatar-md avatar-rounded bg-warning-transparent">
                                                            <i class="las la-list-alt"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">Pending</p>
                                                                <h4 class="fw-semibold mt-1">{{ $pending }}</h4>
                                                            </div>
                                                            <div id="crm-total-deals"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-danger-transparent">
                                                            <i class="las la-list-alt"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">Rejected</p>
                                                                <h4 class="fw-semibold mt-1">{{ $rejected }}</h4>
                                                            </div>
                                                            <div id="crm-total-deals"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('admin.nin.services.list') }}"
                                class="row g-2 mb-3 align-items-end">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}" placeholder="Search Here ...">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ request('date_from') }}" placeholder="Start Date">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ request('date_to') }}" placeholder="End Date">
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table text-nowrap" style="background:#fafafc !important">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Reference Number</th>
                                            <th>Phone No.</th>
                                            <th>Service Type</th>
                                            <th>Reason</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bvn_services as $service)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td>{{ $service->refno }}</td>
                                                <td>{{ $service->phone_number }}</td>
                                                <td>BVN Search Validation</td>
                                                <td>{{ $service->name }}</td>
                                                <td>₦ {{ $service->transactions->amount }}</td>
                                                <td>


                                                    @if ($service->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($service->status == 'resolved')
                                                        <span class="badge bg-success">Resolved</span>
                                                    @elseif($service->status == 'processing')
                                                        <span class="badge bg-primary">Processing</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($service->user && $service->status !== 'rejected')
                                                        <a href="{{ route('admin.bvn-view-request', [$service->id, $request_type]) }}"
                                                            class="btn btn-primary btn-sm text-center">
                                                            <i class="ri-edit-line">View</i>
                                                        </a>

                                                        <a type="button" data-bs-toggle="modal"
                                                            data-id="{{ $service->id }}"
                                                            data-trxamount="{{ $service->transactions->amount }}"
                                                            data-bs-target="#reply" class="btn btn-light btn-sm">
                                                            <i class="bi bi-pencil-square" style="font-size: 0.9rem;"></i>
                                                            Reply
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $bvn_services->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modals -->
                <div class="modal fade" id="reply" tabindex="-1" aria-labelledby="reply" data-bs-keyboard="true"
                    data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="staticBackdropLabel2">Reply Phone Search (#<span
                                        id="sid"></span>)</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" id="statusForm">
                                    @csrf

                                    <!-- Status Selection -->
                                    <div class="mb-3">
                                        <label for="status" class="form-label"><strong>Select
                                                Status</strong></label>
                                        <select name="status" id="status" class="form-select text-dark" required>
                                            <option value="" disabled selected>-- Choose Status --
                                            </option>
                                            <option value="resolved">Resolved</option>
                                            <option value="processing">Processing</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>

                                    <!-- Refund Option -->
                                    <div class="mb-3 d-none" id="refundOption">
                                        <label class="form-label"><strong>Refund Options</strong></label>

                                        <!-- Percentage Selection -->
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input type="radio" name="refund_percentage" value="10"
                                                    id="refund10" class="form-check-input refund-percentage ">
                                                <label for="refund10" class="form-check-label">10%</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="refund_percentage" value="20"
                                                    id="refund20" class="form-check-input refund-percentage">
                                                <label for="refund20" class="form-check-label">20%</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="refund_percentage" value="30"
                                                    id="refund30" class="form-check-input refund-percentage">
                                                <label for="refund30" class="form-check-label">30%</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="refund_percentage" value="50"
                                                    id="refund50" class="form-check-input refund-percentage">
                                                <label for="refund50" class="form-check-label">50%</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="refund_percentage" value="100"
                                                    id="refund100" class="form-check-input refund-percentage">
                                                <label for="refund100" class="form-check-label">100%</label>
                                            </div>
                                        </div>

                                        <!-- Calculated Refund Amount -->
                                        <div class="mt-3">
                                            <label for="refundAmount" class="form-label"><strong>Refund
                                                    Amount
                                                    (₦)</strong></label>
                                            <input type="text" id="refundAmount" name="refundAmount"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- Quill Editor Section -->
                                    <div class="mb-3">
                                        <label for="editor" class="form-label"><strong>Comment</strong></label>
                                        <div id="editor" class="form-control"> </div>
                                        <input type="hidden" name="comment" id="commentInput">
                                        <input type="hidden" name="trxAmount" id="trxAmount">
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            var requestId; // This will store the ID for later use
            var trxAmount;
            $('#reply').on('shown.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                requestId = button.data('id');
                trxAmount = button.data('trxamount');
                $('#trxAmount').val(trxAmount);
                $("#sid").html(requestId);

                // Update form action when modal is shown
                const requestType = 'BVN Phone Search';
                const actionUrl = `/admin/requests/${requestId}/${requestType}/update-bvn-status`;
                document.getElementById('statusForm').setAttribute("action", actionUrl);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill Editor
            const quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Enter your comment...',
            });

            function clear() {
                quill.root.innerHTML = '';
            }

            // Toggle Refund Option
            const statusSelect = document.getElementById('status');
            const refundOption = document.getElementById('refundOption');
            statusSelect.addEventListener('change', function() {
                clear();
                if (this.value === 'rejected') {
                    refundOption.classList.remove('d-none');
                } else if (this.value === 'processing') {
                    quill.root.innerHTML =
                        "Thank you for reaching out. Your request has been received and is currently being processed. We will notify you promptly upon resolution."
                } else {
                    refundOption.classList.add('d-none');
                }
            });

            // Handle Form Submission
            const form = document.getElementById('statusForm');

            form.addEventListener('submit', function(event) {
                // Get Quill content as HTML
                const commentContent = quill.root.innerHTML;
                // Set it in the hidden input
                document.getElementById('commentInput').value = commentContent;

                // Optionally: Validate the comment is not empty
                if (quill.getText().trim().length === 0) {
                    event.preventDefault();
                    alert('Please add a comment before submitting.');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const refundOption = document.getElementById('refundOption');
            const refundAmountInput = document.getElementById('refundAmount');
            const refundPercentageRadios = document.querySelectorAll('.refund-percentage');

            // Transaction amount (Replace with actual value if dynamic)
            const transactionAmount = document.getElementById('trxAmount').value;



            // Show or hide refund option based on status
            statusSelect.addEventListener('change', function() {
                if (this.value === 'rejected') {
                    refundOption.classList.remove('d-none');
                    refundAmountInput.setAttribute('required', 'required');

                } else {
                    refundOption.classList.add('d-none');
                    refundAmountInput.removeAttribute('required');
                    refundAmountInput.value = '';
                    refundPercentageRadios.forEach(radio => (radio.checked = false));
                }
            });

            // Calculate refund amount based on selected percentage
            refundPercentageRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const percentage = parseInt(this.value, 10);
                    const refundAmount = (transactionAmount * percentage) / 100;
                    refundAmountInput.value = `${refundAmount}`;
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            var requestId; // This will store the ID for later use
            var trxAmount;

            $('#reply').on('shown.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                requestId = button.data('id');
                trxAmount = button.data('trxamount');
                $('#trxAmount').val(trxAmount);
                $("#sid").html(requestId);

                // Update form action when modal is shown
                const requestType = 'BVN Phone Search';
                const actionUrl = `/admin/requests/${requestId}/${requestType}/update-bvn-status`;
                document.getElementById('statusForm').setAttribute("action", actionUrl);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill Editor
            const quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Enter your comment...',
            });

            function clear() {
                quill.root.innerHTML = '';
            }

            // Get DOM elements
            const statusSelect = document.getElementById('status');
            const refundOption = document.getElementById('refundOption');
            const refundAmountInput = document.getElementById('refundAmount');
            const refundPercentageRadios = document.querySelectorAll('.refund-percentage');
            const transactionAmountElement = document.getElementById('trxAmount');

            // Toggle Refund Option and handle status changes
            statusSelect.addEventListener('change', function() {
                clear();
                if (this.value === 'rejected') {
                    refundOption.classList.remove('d-none');
                    refundAmountInput.setAttribute('required', 'required');
                } else if (this.value === 'processing') {
                    quill.root.innerHTML =
                        "Thank you for reaching out. Your request has been received and is currently being processed. We will notify you promptly upon resolution.";
                    refundOption.classList.add('d-none');
                    refundAmountInput.removeAttribute('required');
                    refundAmountInput.value = '';
                    refundPercentageRadios.forEach(radio => (radio.checked = false));
                } else {
                    refundOption.classList.add('d-none');
                    refundAmountInput.removeAttribute('required');
                    refundAmountInput.value = '';
                    refundPercentageRadios.forEach(radio => (radio.checked = false));
                }
            });

            // Calculate refund amount based on selected percentage
            refundPercentageRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Get the current transaction amount value and convert to number
                    const transactionAmount = parseFloat(transactionAmountElement.value) || 0;
                    const percentage = parseInt(this.value, 10);
                    const refundAmount = (transactionAmount * percentage) / 100;
                    refundAmountInput.value = refundAmount.toFixed(2); // Format to 2 decimal places
                });
            });

            // Handle Form Submission
            const form = document.getElementById('statusForm');
            form.addEventListener('submit', function(event) {
                // Get Quill content as HTML
                const commentContent = quill.root.innerHTML;
                // Set it in the hidden input
                document.getElementById('commentInput').value = commentContent;

                // Optionally: Validate the comment is not empty
                if (quill.getText().trim().length === 0) {
                    event.preventDefault();
                    alert('Please add a comment before submitting.');
                }
            });
        });
    </script>
    <!-- Quill Editor JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <!-- Internal Quill JS -->
@endpush
