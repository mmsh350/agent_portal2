<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bvn_enrollments', function (Blueprint $table) {
            DB::statement("ALTER TABLE bvn_enrollments MODIFY COLUMN status ENUM('submitted', 'processing', 'successful', 'rejected') DEFAULT 'submitted'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bvn_enrollments', function (Blueprint $table) {
            DB::statement("ALTER TABLE bvn_enrollments MODIFY COLUMN status ENUM('submitted', 'successful', 'rejected') DEFAULT 'submitted'");
        });
    }
};
