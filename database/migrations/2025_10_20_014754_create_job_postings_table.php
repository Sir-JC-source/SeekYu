<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->string('job_post_id')->unique(); // e.g., JOB-xxxx
            $table->string('title');
            $table->enum('position', ['Security Guard', 'Head Guard']);
            $table->text('description');
            $table->enum('type_of_employment', ['Contractual', 'Full-Time']);
            $table->string('location');
            $table->unsignedBigInteger('created_by')->nullable(); // User who created the job posting
            $table->foreign('created_by')->references('id')->on('registered_users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
