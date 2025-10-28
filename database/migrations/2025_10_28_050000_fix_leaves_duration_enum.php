<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE leaves MODIFY COLUMN duration ENUM('Whole Shift', 'Half-Shift Early Out', 'Half-Shift Late In', 'Multi-Day')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE leaves MODIFY COLUMN duration ENUM('Whole Shift', 'Half-Shift Early Out', 'Half-Shift Late In')");
    }
};
