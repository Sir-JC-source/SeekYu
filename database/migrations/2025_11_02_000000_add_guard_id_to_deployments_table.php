<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->unsignedBigInteger('guard_id')->nullable()->after('employee_id');
            $table->foreign('guard_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->dropForeign(['guard_id']);
            $table->dropColumn('guard_id');
        });
    }
};
