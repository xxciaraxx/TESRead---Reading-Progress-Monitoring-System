<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Remove the reading_levels table and all foreign key columns.
 * Reading level is now derived automatically from risk_level
 * using the Phil-IRI mapping in Student::getReadingLevelLabelAttribute().
 */
return new class extends Migration
{
    public function up(): void
    {
        // Drop FK column from students
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'reading_level_id')) {
                $table->dropForeign(['reading_level_id']);
                $table->dropColumn('reading_level_id');
            }
        });

        // Drop FK column from assessments
        Schema::table('assessments', function (Blueprint $table) {
            if (Schema::hasColumn('assessments', 'reading_level_id')) {
                $table->dropForeign(['reading_level_id']);
                $table->dropColumn('reading_level_id');
            }
        });

        // Drop the reading_levels table
        Schema::dropIfExists('reading_levels');
    }

    public function down(): void
    {
        // Recreate reading_levels table
        Schema::create('reading_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('grade_level');
            $table->string('color_code')->default('#003A8C');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('reading_level_id')->nullable()->constrained('reading_levels')->nullOnDelete();
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->foreignId('reading_level_id')->nullable()->constrained('reading_levels')->nullOnDelete();
        });
    }
};
