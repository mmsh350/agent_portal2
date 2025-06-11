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
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('idno');
            $table->enum('type', ['', 'BVN', 'NIN']);
            $table->string('nin')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phoneno')->nullable();
            $table->string('email')->nullable();
            $table->date('dob');
            $table->string('gender');
            $table->string('address')->nullable();
            $table->string('enrollment_branch')->nullable();
            $table->string('enrollment_bank')->nullable();
            $table->longText('photo');
            $table->date('registration_date')->nullable();
            $table->string('title')->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('trackingId')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
