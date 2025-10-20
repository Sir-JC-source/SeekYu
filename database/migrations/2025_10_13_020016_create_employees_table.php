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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number', 8)->unique();
            $table->string('full_name');
            $table->enum('position', ['Admin', 'HR Officer', 'Head Guard', 'Security Guard']);
            $table->date('date_hired');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('employee_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
