<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change ENUM to include 'Approved' instead of 'Accepted'
        Schema::table('leaves', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
    $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending')->change();
        });
    }
};
