<?php

namespace App\Http\Controllers;

use App\Models\BonusHistory;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $loginUserId = auth()->user()->id;

        $wallet = Wallet::where('user_id', )->first();

        //get all referral bonus
        $bonus_balance = BonusHistory::where('user_id', $loginUserId)->sum('amount');

        $unclaimed_balance = $wallet->bonus ?? 0;

        $claimed_balance = $bonus_balance -  $unclaimed_balance;

        $notificationsEnabled = Auth::user()->notification;

        $transaction = DB::table('claim_counts')->first();

        $transaction_count = $transaction->transaction_count ?? 5;

        $users = User::where('referred_by', $loginUserId)
            ->withCount('transactions')
            ->paginate(10);

        $userIds = $users->pluck('id');
        $bonusHistories = BonusHistory::whereIn('referred_user_id', $userIds)->get();

        $bonusHistoriesGrouped = $bonusHistories->groupBy('referred_user_id');

        $usersWithBonuses = $users->map(function ($user) use ($bonusHistoriesGrouped) {

            $totalBonusAmount = $bonusHistoriesGrouped->has($user->id)
                ? $bonusHistoriesGrouped->get($user->id)->sum('amount')
                : 0;

            $user->total_bonus_amount = $totalBonusAmount;

            return $user;
        });

        $users->setCollection($usersWithBonuses);

        return view('user.wallet', [
            'transaction_count' => $transaction_count,
            'users' =>  $users,
        ]);


    }
    public function claimBonus($user_id)
    {
         $loginUserId = auth()->user()->id;

        $count = 0;
        $claim_id = 0;

        $transaction = DB::table('claim_counts')->first();
        $transaction_count = $transaction->transaction_count ?? 5;

        $user = User::where('id', $user_id)->first();
        $count = $user->transactions()->count();
        $claim_id = $user->claim_id;


        if ($user_id == $loginUserId) {
            return redirect()->back()->with('error', 'Nice try! But our system is one step ahead!');
        } elseif ($claim_id == 0 && $count >= $transaction_count) {

            $bonus = BonusHistory::where('referred_user_id', $user_id)->first();

            $wallet = Wallet::where('user_id', $bonus->user_id)->first();

            $new_wallet_balance = $wallet->balance + $bonus->amount;
            $new_deposit_balance = $wallet->deposit + $bonus->amount;
            $new_bonus_balance = max(0, $wallet->bonus - $bonus->amount);

            Wallet::where('user_id',  $bonus->user_id)->update([
                'balance' => $new_wallet_balance,
                'deposit' => $new_deposit_balance,
                'bonus' => $new_bonus_balance
            ]);

            User::where('id', $user_id)->update(['claim_id' => 1]);

            $serviceDesc = 'Bonus claim to wallet  ₦' . number_format($bonus->amount, 2);

            $this->transactionService->createTransaction($bonus->user_id, $bonus->amount, 'Bonus Claim', $serviceDesc,  'Wallet', 'Approved');

            $successMessage = 'Your bonus has been claimed and added to your main wallet. Congratulations!';

            return redirect()->back()->with('success', $successMessage);
        } else {
            return redirect()->back()->with('error', 'You are not eligible to claim the bonus at this time. Please ensure your referrals have completed the required minimum of 5 transactions to qualify.');
        }
    }

}
