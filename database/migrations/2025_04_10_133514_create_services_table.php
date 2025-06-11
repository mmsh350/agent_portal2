<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_code');
            $table->string('name');
            $table->enum('category', [
                'Upgrades',
                'Verifications',
                'Airtime',
                'Data',
                'A2C',
                'Electricity',
                'Cable Sub',
                'EPIN',
                'Agency',
                'Charges'
            ])->default('Verifications');
            $table->string('type')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
