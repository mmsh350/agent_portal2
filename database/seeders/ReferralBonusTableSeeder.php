<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferralBonusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('referral_bonus')->updateOrInsert(
                ['id' => 1],
                [
                    'bonus' => '100',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
             );
    }
}
