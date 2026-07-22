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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('status')->default('pending');
        $table->string('priority')->default('normal');
        $table->foreignId('audit_point_id')->constrained('audit_points')->onDelete('cascade');
        $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
        $table->foreignId('assigned_manager')->nullable()->constrained('users')->onDelete('set null');
        $table->date('due_date')->nullable();
        $table->softDeletes();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
