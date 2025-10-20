<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incident_report_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_report_id')->constrained('incident_reports')->onDelete('cascade');
            $table->string('name');
            $table->string('role');
            $table->string('contact');
            $table->text('statement');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('incident_report_parties');
    }
};
