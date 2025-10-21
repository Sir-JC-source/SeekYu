<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->string('contact_no', 20)->nullable()->after('first_login');
        });
    }

    public function down(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->dropColumn('contact_no');
        });
    }
};
