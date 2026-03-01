<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete();
            $table->foreignId('assessment_id')
                  ->constrained('assessments')
                  ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->text('intervention_notes')->nullable();
            $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
            $table->date('started_on');
            $table->date('ended_on')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
