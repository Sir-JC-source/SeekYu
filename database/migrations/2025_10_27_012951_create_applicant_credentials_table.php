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
        Schema::create('applicant_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('registered_users')->onDelete('cascade');
            $table->string('license_no')->nullable();
            $table->text('certifications')->nullable();
            $table->date('license_expiration_date')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->json('work_history')->nullable(); // Store as JSON array
            $table->json('skills')->nullable(); // Store as JSON array
            $table->string('resume_path')->nullable();
            $table->string('license_path')->nullable();
            $table->string('training_certificate_path')->nullable();
            $table->string('nbi_clearance_path')->nullable();
            $table->boolean('is_first_time')->default(true);
            $table->boolean('data_consent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_credentials');
    }
};
