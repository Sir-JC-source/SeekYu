<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_credentials', function (Blueprint $table) {
            $table->string('sss_number')->nullable()->after('years_of_experience');
            $table->string('pagibig_number')->nullable()->after('sss_number');
            $table->string('philhealth_number')->nullable()->after('pagibig_number');
        });
    }

    public function down(): void
    {
        Schema::table('applicant_credentials', function (Blueprint $table) {
            $table->dropColumn(['sss_number', 'pagibig_number', 'philhealth_number']);
        });
    }
};
