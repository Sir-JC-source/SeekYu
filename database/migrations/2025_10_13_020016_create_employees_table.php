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
            $table->string('employee_number', 5)->unique();
            $table->string('full_name');
            $table->enum('position', [
                'Super Administrator',
                'Administrator',
                'HR Officer',
                'Head Guard',
                'Security Guard'
            ]);
            $table->date('date_hired');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            
            // ✅ Added missing fields
            $table->string('designation')->nullable();
            $table->string('deployment_status')->default('assigned');
            $table->string('employee_image')->nullable();
            $table->string('contact_no', 20)->default('0000000000'); // ensure not null
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->time('shift_in')->nullable();
            $table->time('shift_out')->nullable();
            $table->unsignedBigInteger('assigned_head_guard_id')->nullable();

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
