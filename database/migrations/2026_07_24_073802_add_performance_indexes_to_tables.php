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
        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['assigned_manager', 'status'], 'idx_tasks_manager_status');
        });

        Schema::table('audit_points', function (Blueprint $table) {
            $table->index('is_active', 'idx_audit_points_is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('idx_tasks_manager_status');
        });

        Schema::table('audit_points', function (Blueprint $table) {
            $table->dropIndex('idx_audit_points_is_active');
        });
    }
};
