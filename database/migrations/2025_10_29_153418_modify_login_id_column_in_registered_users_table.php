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
        Schema::table('registered_users', function (Blueprint $table) {
            $table->string('login_id')->change(); // Remove the length limit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->string('login_id', 5)->change(); // Revert back to 5 characters
        });
    }
};
