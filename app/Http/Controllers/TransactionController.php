<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function reciept(Request $request)
    {

        $loginUserId = Auth::id();

        $transaction = Transaction::where('referenceId', $request->referenceId)
            ->where('user_id', $loginUserId)
            ->first();

        if (! $transaction) {
            abort(404);
        }

        return view('user.receipt', ['transaction' => $transaction]);
    }

    public function recieptAdmin(Request $request)
    {

        $transaction = Transaction::where('referenceId', $request->referenceId)->first();

        if (! $transaction) {
            abort(404);
        }

        return view('user.receipt', ['transaction' => $transaction]);
    }
}
