<?php

namespace App\Services;

use App\Models\Services;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class WalletService
{

    /**
     * Credit the developer's wallet and log the transaction.
     *
     * @param string $payerName
     * @param string $payerEmail
     * @param string $payerPhone
     * @param string $referenceId
     * @param string $serviceType
     * @return void
     */
    public function creditDeveloperWallet($payerName, $payerEmail, $payerPhone, $referenceId, $serviceType)
    {
        // Fetch the service fee from the database
        $serviceFee = Services::where('type', $serviceType)->value('amount');

        if (!$serviceFee) {
            throw new \Exception("Service type '{$serviceType}' not found or fee is not set.");
        }

        $developerId = config('wallet.developer_id');  

        // Find the developer user
        $developer = User::find($developerId);

        if ($developer) {
            // Step 1: Fetch the developer's current wallet balance
            $wallet = Wallet::where('user_id', $developerId)->first();

            if ($wallet) {
        
                // Increment the wallet balance by the service fee
                $newBalance = $wallet->balance + $serviceFee;

                $newDeposit = $wallet->deposit + $serviceFee;
                // Update the wallet balance

                $wallet->update([
                    'balance' => $newBalance,
                    'deposit' => $newDeposit,
                ]);

                // Step 2: Record the transaction in the transactions table
                Transaction::create([
                    'user_id' => $developerId,
                    'payer_name' => $payerName,
                    'payer_email' => $payerEmail,
                    'payer_phone' => $payerPhone,
                    'referenceId' => $referenceId,
                    'service_type' => ucfirst(str_replace('_', ' ', $serviceType)) . ' Fee',
                    'service_description' => 'Developer wallet credited with ₦' . number_format($serviceFee, 2),
                    'amount' => $serviceFee,
                    'gateway' => 'Internal Transfer',
                    'status' => 'Approved',
                ]);
            } else {
                throw new \Exception("Wallet not found for developer.");
            }
        }
    }
}
