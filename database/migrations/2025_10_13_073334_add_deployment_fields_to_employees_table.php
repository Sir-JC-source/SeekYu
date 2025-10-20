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
        Schema::table('employees', function (Blueprint $table) {
            // Add shift_in if it doesn't exist
            if (!Schema::hasColumn('employees', 'shift_in')) {
                $table->time('shift_in')->nullable()->after('employee_image');
            }

            // Add shift_out if it doesn't exist
            if (!Schema::hasColumn('employees', 'shift_out')) {
                $table->time('shift_out')->nullable()->after('shift_in');
            }

            // Add designation if it doesn't exist
            if (!Schema::hasColumn('employees', 'designation')) {
                $table->string('designation')->nullable()->after('shift_out');
            }

            // Add assigned_head_guard_id if it doesn't exist
            if (!Schema::hasColumn('employees', 'assigned_head_guard_id')) {
                $table->unsignedBigInteger('assigned_head_guard_id')->nullable()->after('designation');
                
                // Add foreign key safely
                $table->foreign('assigned_head_guard_id')
                      ->references('id')
                      ->on('employees')
                      ->onDelete('set null');
            }

            // Add deployment_status if it doesn't exist
            if (!Schema::hasColumn('employees', 'deployment_status')) {
                $table->string('deployment_status')->default('Not Deployed')->after('assigned_head_guard_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'deployment_status')) {
                $table->dropColumn('deployment_status');
            }
            if (Schema::hasColumn('employees', 'assigned_head_guard_id')) {
                $table->dropForeign(['assigned_head_guard_id']);
                $table->dropColumn('assigned_head_guard_id');
            }
            if (Schema::hasColumn('employees', 'designation')) {
                $table->dropColumn('designation');
            }
            if (Schema::hasColumn('employees', 'shift_out')) {
                $table->dropColumn('shift_out');
            }
            if (Schema::hasColumn('employees', 'shift_in')) {
                $table->dropColumn('shift_in');
            }
        });
    }
};
