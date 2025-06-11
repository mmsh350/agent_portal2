<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_name' => 'AGENT PORTAL',
            'short_name' => 'AGENT PORTAL',
            'logo' => 'logo.png',
            'mini_logo' => 'mini-logo.png',
            'login_background_image' => 'login-bg-1.jpg',
            'registration_background_image' => 'register-bg-1.jpg',
            'favicon' => 'favicon.ico',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
