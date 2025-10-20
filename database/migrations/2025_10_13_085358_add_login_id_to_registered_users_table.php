<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->string('login_id', 5)->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->dropColumn('login_id');
        });
    }
};
