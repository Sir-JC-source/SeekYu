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
            $table->integer('points')->default(0)->after('leave_credits');
            $table->json('badges')->nullable()->after('points');
            $table->integer('level')->default(1)->after('badges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->dropColumn(['points', 'badges', 'level']);
        });
    }
};
