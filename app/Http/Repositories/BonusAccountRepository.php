<?php

namespace App\Http\Repositories;

use App\Models\Bonus;
use App\Models\User;

class BonusAccountRepository
{
    public function creditBonusAccount($id)
    {

        // //Get User Id
        // $getReferralbonus = User::select('referral_bonus')->where('id', $id)->first();

        // //Update Wallet balance
        // $bonus = Bonus::where('user_id', $id)->first();
        // $balance = $bonus->balance + $getReferralbonus->referral_bonus;
        // $deposit = $bonus->deposit + $getReferralbonus->referral_bonus;

        // //Update Bonus Account
        // Bonus::where('user_id', $id)->update([
        //     'balance' => $balance,
        //     'deposit' => $deposit,
        // ]);

    }
}
