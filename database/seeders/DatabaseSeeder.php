<?php

namespace Database\Seeders;

use App\Models\ClaimCount;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        SiteSetting::truncate();
        Service::truncate();
        ClaimCount::truncate();


        User::updateOrCreate(
            ['email' => 'admin@agentportal.ng'],
            [
                'name' => 'Agent Portal',
                'email_verified_at' => now(),
                'password' => Hash::make('@passwd12345'),
                'role' => 'super admin',
            ]
        );

        SiteSetting::factory(1)->create();

        foreach (Service::factory()->withCustomData() as $data) {
            Service::create($data);
        }

        ClaimCount::factory(1)->create();

        $this->call([
            ReferralBonusTableSeeder::class,
        ]);
    }
}
