<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    /**
     * Generate a unique 12-character reference number.
     *
     * @return string
     */
    public function generateReferenceNumber(): string
    {
        $characters = '123456123456789071234567890890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $reference = '';

        for ($i = 0; $i < 12; $i++) {
            $reference .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $reference;
    }

    /**
     * Create a new transaction.
     *
     * @param int $userId
     * @param float $amount
     * @return Transaction
     */

    public function createTransaction(int $userId, float $amount, string $serviceType, string $serviceDesc, string $gateway = 'Wallet', string $status = 'Pending'): ?Transaction
    {


        $user = User::findOrFail($userId);

        // Create the transaction
        $transaction = Transaction::create([
            'user_id' => $userId,
            'payer_name' => $user->name,
            'payer_email' => $user->email,
            'amount' => $amount,
            'payer_phone' => $user->phone_number,
            'referenceId' => $this->generateReferenceNumber(),
            'service_type' => $serviceType,
            'service_description' => $serviceDesc,
            'gateway' => $gateway,
            'status' => $status
        ]);

        return $transaction;
    }
}
