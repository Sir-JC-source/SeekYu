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
            // Add login_id for admin, hr-officer, security-guard, head-guard, client, applicant
            if (!Schema::hasColumn('registered_users', 'login_id')) {
                $table->string('login_id', 5)->unique()->nullable()->after('faculty_no');
            }

            // Add faculty_no if it doesn't exist
            if (!Schema::hasColumn('registered_users', 'faculty_no')) {
                $table->string('faculty_no')->unique()->nullable()->after('student_no');
            }

            // Change first_login to boolean if it exists as string
            if (Schema::hasColumn('registered_users', 'first_login')) {
                $table->boolean('first_login')->default(true)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('registered_users', 'login_id')) {
                $table->dropColumn('login_id');
            }

            if (Schema::hasColumn('registered_users', 'faculty_no')) {
                $table->dropColumn('faculty_no');
            }

            // Revert first_login back to string (optional)
            if (Schema::hasColumn('registered_users', 'first_login')) {
                $table->string('first_login')->default('no')->change();
            }
        });
    }
};
