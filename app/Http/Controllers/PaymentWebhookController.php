<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class PaymentWebhookController extends Controller
{

    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('billstack.credentials.secretkey');
    }

    public function handleWebhook(Request $request)
    {

        // Verify the signature
        if (! $this->verifySignature($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Process the webhook payload
        $payload = $request->all();
        Log::info('Billstack webhook received:', $payload);

        switch ($payload['event']) {
            case 'PAYMENT_NOTIFICATION':
                $this->handleSuccessfulTransaction($payload);
                break;
            default:
                Log::info('Unhandled event type: ' . $payload['event']);
        }

        return response()->json(['status' => 'success']);
    }

    private function verifySignature(Request $request)
    {
        $signature = $request->header('x-wiaxy-signature');
        if(!$signature)
        {
            Log::warning('Billsatck webhook signature not found.');

            return false;
        }
        $computedSignature =  md5($this->secretKey);

        if ($signature !==  $computedSignature) {
            Log::warning('Billstack webhook signature mismatch.', ['received' => $signature, 'key' => $computedSignature]);

            return false;
        }

        return true;
    }

    private function handleSuccessfulTransaction($payload)
    {
        $eventData = $payload['data'];

        if ($eventData['type'] === 'RESERVED_ACCOUNT_TRANSACTION') {
            $this->processTransaction($eventData);
        }
    }

    private function processTransaction($eventData)
    {
        $transactionReference = $eventData['transaction_ref'];
        $accountReference = $eventData['reference'];
        $amountPaid = $eventData['amount'];
        $email = $eventData['customer']['email'];

        $transaction = Transaction::where('referenceId', $transactionReference)->first();

        Log::warning('Existing Transaction. ', ['Transaction' => $transaction]);

        if (! $transaction) {
            $this->createNewTransaction($email, $transactionReference,$accountReference, $amountPaid);
        }
    }

    private function createNewTransaction($email, $transactionReference, $accountReference, $amountPaid)
    {
        $virtualAccount = VirtualAccount::where('accountReference', trim($accountReference))->first();

        $user = User::where('id', $virtualAccount->user_id)->first();

        if ($user) {
            $this->insertTransaction($user->id, $transactionReference, $amountPaid, $user->name, $email, $user->phone_number);
            $this->updateWalletBalance($user->id, $amountPaid);
        }
    }

    private function insertTransaction($userId, $transactionReference, $amountPaid, $payerName, $payerEmail, $payerPhone)
    {
        $fee = $this->calculateFee($amountPaid);

        $netAmount = round($amountPaid - $fee, 2);

        Transaction::insert([
            'user_id' => $userId,
            'payer_name' => $payerName,
            'payer_email' => $payerEmail,
            'payer_phone' => $payerPhone,
            'referenceId' => $transactionReference,
            'service_type' => 'Wallet Topup',
            'service_description' => 'Your wallet has been credited with ₦' . number_format($amountPaid, 2),
            'amount' => $netAmount,
            'gateway' => 'Billstack',
            'status' => 'Approved',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function calculateFee($amountPaid){

       return  $fee = round($amountPaid * 0.005, 2);

    }
    private function updateWalletBalance($userId, $amountPaid)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet) {  // Check if wallet exists

            //$fee = round($amountPaid * 0.019, 2);
            $fee = $this->calculateFee($amountPaid);

            // Subtract fee from the paid amount
            $netAmount = round($amountPaid - $fee, 2);

            // Calculate the new balance by adding the amount paid
            $balance = round($wallet->balance + (float) $netAmount, 2);
            $depositbalance = round($wallet->deposit + (float) $netAmount, 2);

            // Log the new balance for debugging
            Log::info("Fee (1.9%): $fee, Net Amount: $netAmount");
            Log::info('New Balance: ', [$balance]);

            // Update the wallet with the new balance
            $wallet->update([
                'balance' => $balance,
                'deposit' => $depositbalance,
            ]);
        } else {
            // Log a warning if the wallet is not found for the given user ID
            Log::warning('Wallet not found for user ID: ' . $userId);
        }
    }
}
