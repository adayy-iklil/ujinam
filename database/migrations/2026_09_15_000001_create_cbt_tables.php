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
        // 1. Academic Years
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 2025/2026
            $table->integer('start_year');
            $table->integer('end_year');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // 2. Majors
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. RPL, DKV, AK, MP, BR
            $table->string('name'); // e.g. Rekayasa Perangkat Lunak
            $table->timestamps();
        });

        // 3. Classes
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('major_id')->constrained('majors')->cascadeOnDelete();
            $table->enum('grade', ['X', 'XI', 'XII']);
            $table->string('name'); // e.g. XII RPL, XII DKV 1
            $table->integer('class_number')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 5. Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Teacher Subjects (Pivot)
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['teacher_id', 'subject_id']);
        });

        // 7. Exams
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->integer('duration_minutes');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['draft', 'published', 'ongoing', 'finished'])->default('draft');
            $table->boolean('show_result')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('randomize_options')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Exam Classes (Pivot)
        Schema::create('exam_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['exam_id', 'class_id']);
        });

        // 9. Questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice'])->default('multiple_choice');
            $table->float('points')->default(1.0);
            $table->integer('question_order')->default(1);
            $table->timestamps();
        });

        // 10. Question Options
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('option_key', 10); // A, B, C, D, E
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('option_order')->default(1);
            $table->timestamps();
        });

        // 11. Exam Attempts
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('expires_at');
            $table->enum('status', ['not_started', 'in_progress', 'submitted', 'force_logged_out', 'locked'])->default('in_progress');
            $table->float('score')->nullable();
            $table->integer('correct_answers')->nullable();
            $table->integer('wrong_answers')->nullable();
            $table->integer('violation_count')->default(0);
            $table->boolean('force_logged_out')->default(false);
            $table->foreignId('reset_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reset_at')->nullable();
            $table->timestamps();
        });

        // 12. Exam Attempt Questions (Deterministic Order)
        Schema::create('exam_attempt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->integer('question_order');
            $table->timestamps();
            $table->unique(['attempt_id', 'question_id']);
        });

        // 13. Student Answers
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->dateTime('answered_at')->nullable();
            $table->timestamps();
            $table->unique(['attempt_id', 'question_id']);
        });

        // 14. Exam Violations
        Schema::create('exam_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->enum('violation_type', ['tab_hidden', 'window_blur', 'fullscreen_exit', 'manual_logout']);
            $table->string('description')->nullable();
            $table->dateTime('occurred_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 15. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('exam_violations');
        Schema::dropIfExists('student_answers');
        Schema::dropIfExists('exam_attempt_questions');
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exam_classes');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('teacher_subjects');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('majors');
        Schema::dropIfExists('academic_years');
    }
};
