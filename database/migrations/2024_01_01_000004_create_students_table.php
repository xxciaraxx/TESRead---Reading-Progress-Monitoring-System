<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('lrn')->unique()->nullable();    // Learner Reference Number
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('profile_photo')->nullable();
            $table->foreignId('section_id')
                  ->nullable()
                  ->constrained('sections')
                  ->nullOnDelete();
            $table->foreignId('teacher_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->foreignId('reading_level_id')
                  ->nullable()
                  ->constrained('reading_levels')
                  ->nullOnDelete();
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
