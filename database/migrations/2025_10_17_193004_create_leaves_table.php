<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id'); // FK to registered_users
            $table->string('requestor');
            $table->enum('leave_type', ['Sick Leave', 'Vacation Leave']);
            $table->text('reason');
            $table->enum('duration', ['Whole Shift', 'Half-Shift Early Out', 'Half-Shift Late In']);
            $table->date('date_from');
            $table->date('date_to');
            $table->string('position');
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->integer('leave_credits')->default(0);
            $table->string('approved_by')->nullable();
            $table->string('rejected_by')->nullable();
            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('registered_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
