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
            $table->enum('employment_type', ['Contractual', 'Full-Time']);
            $table->string('location');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
