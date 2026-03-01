<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('reading_level_id')
                  ->nullable()
                  ->constrained('reading_levels')
                  ->nullOnDelete();
            $table->decimal('fluency_score', 5, 2)->default(0);           // 0–100
            $table->decimal('comprehension_score', 5, 2)->default(0);     // 0–100
            $table->integer('reading_sessions_per_week')->default(0);
            $table->text('notes')->nullable();
            $table->enum('risk_level', [
                'Below Expected Literacy Standard',
                'Approaching Expected Literacy Standard',
                'Meeting or Exceeding Literacy Standard',
            ])->nullable();
            $table->date('assessed_on');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
