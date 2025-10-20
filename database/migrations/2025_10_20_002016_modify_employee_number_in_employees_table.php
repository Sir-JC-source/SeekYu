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
        Schema::table('employees', function (Blueprint $table) {
            // Change length from 5 to 8
            $table->string('employee_number', 8)->change();
            // Do NOT add unique() again, index already exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Revert length back to 5
            $table->string('employee_number', 5)->change();
            // Keep existing unique index intact
        });
    }
};
