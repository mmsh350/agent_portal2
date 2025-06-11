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
        Schema::table('verifications', function (Blueprint $table) {
            $table->string('town')->nullable()->after('lga');
            $table->string('residence_state')->nullable()->after('town');
            $table->string('residence_lga')->nullable()->after('residence_state');
            $table->string('residence_town')->nullable()->after('residence_lga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->dropColumn(['residence_state','residence_lga','residence_town','town']);
        });
    }
};
