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
            $table->string('province')->nullable()->after('contact_no');
            $table->string('city')->nullable()->after('province');
            $table->string('barangay')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_users', function (Blueprint $table) {
            $table->dropColumn(['province', 'city', 'barangay']);
        });
    }
};
