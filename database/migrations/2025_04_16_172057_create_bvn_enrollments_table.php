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
        Schema::create('bvn_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('refno');
            $table->string('fullname');
            $table->string('state');
            $table->string('lga');
            $table->longText('address');
            $table->string('city');
            $table->string('bvn');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('bank_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('username');
            $table->enum('status', ['submitted', 'successful', 'rejected'])->default('submitted');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bvn_enrollments');
    }
};
