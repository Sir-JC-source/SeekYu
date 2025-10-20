<?php 


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('incident_name');
            $table->date('date_of_incident');
            $table->string('location');
            $table->string('specific_area');
            $table->text('incident_description');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('incident_reports');
    }
};
