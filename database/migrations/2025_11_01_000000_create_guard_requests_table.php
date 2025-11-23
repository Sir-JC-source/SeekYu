<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('guard_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('registered_users')->onDelete('cascade');
            $table->integer('number_of_guards');
            $table->text('request_details')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guard_requests');
    }
}
