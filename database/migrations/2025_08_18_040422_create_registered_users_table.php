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
        Schema::create('registered_users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->unique();
            $table->string('student_no')->unique()->nullable();
            $table->string('faculty_no')->unique()->nullable();
            $table->string('login_id', 5)->unique()->nullable(); // Added for admin/hr/security/etc
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('password')->nullable();
            $table->string('role')->nullable();
            $table->string('account_status')->default('pending');
            $table->string('profile_picture')->nullable();
            $table->string('status')->default('active');
            $table->boolean('first_login')->default(true); // changed from string to boolean
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registered_users');
    }
};
