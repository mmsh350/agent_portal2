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
        Schema::create('bonus_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The user who receives the bonus
            $table->unsignedBigInteger('referred_user_id'); // The user who was referred
            $table->decimal('amount', 10, 2); // Bonus amount
            $table->string('type')->default('referral'); // Type of bonus (e.g., referral, deposit, etc.)
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_histories');
    }
};
