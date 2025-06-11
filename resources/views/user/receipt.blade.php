
@extends('layouts.receipt')

@section('title', 'Transaction Receipt')

@section('content')

<div class="receipt-container" id="receipt">
    <div class="receipt-header">
        <i class="fas fa-check-circle"></i>
        <h2>Transaction Receipt</h2>
        <p>Transaction successfully processed</p>
    </div>

    <table class="table receipt-table">
        <tbody>
            <tr>
                <th><i class="fas fa-receipt"></i> Reference No.</th>
                <td>{{ strtoupper($transaction->referenceId) }}</td>
            </tr>
            <tr>
                <th><i class="fas fa-tag"></i> Service Type</th>
                <td>{{ strtoupper($transaction->service_type) }}</td>
            </tr>
            <tr>
                <th><i class="fas fa-info-circle"></i> Description</th>
                <td>{{ $transaction->service_description }}</td>
            </tr>
            <tr>
                <th><i class="fas fa-money-bill"></i> Amount</th>
                <td>₦{{ number_format($transaction->amount, 2) }}</td>
            </tr>
            <tr>
                <th><i class="fas fa-check-circle"></i> Status</th>
                <td class="status-approved">{{ strtoupper($transaction->status) }}</td>
            </tr>
            <tr>
                <th><i class="fas fa-calendar-alt"></i> Date</th>
                <td>{{ $transaction->created_at->format('F j, Y, g:i a') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-amount">
        <strong>Total: ₦{{ number_format($transaction->amount, 2) }}</strong>
    </div>

    <div class="receipt-footer">
        <p>Thank you for your business!</p>
    </div>

    <div class="buttons-container">
        <button class="btn btn-primary" onclick="printReceipt()">
            <i class="fas fa-print"></i> Print
        </button>
        <button class="btn btn-secondary" id="shareButton">
            <i class="fas fa-share"></i> Share
        </button>
         <button id="downloadButton" class="btn btn-success">
        <i class="fas fa-download"></i> Download
    </button>
    </div>
</div>

@endsection



