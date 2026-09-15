<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ExamController as TeacherExamController;
use App\Http\Controllers\Teacher\ParticipantController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Teacher\ResultController as TeacherResultController;
use Illuminate\Support\Facades\Route;

// Redirect root to student or teacher login
Route::get('/', function () {
    if (auth('student')->check()) {
        return redirect()->route('siswa.dashboard');
    }
    if (auth('web')->check()) {
        $user = auth('web')->user();
        return redirect()->route($user->isSuperadmin() ? 'admin.dashboard' : 'guru.dashboard');
    }
    return redirect()->route('siswa.login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showUserLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/logout', [LoginController::class, 'logoutUser'])->name('logout');

Route::get('/siswa/login', [LoginController::class, 'showStudentLoginForm'])->name('siswa.login');
Route::post('/siswa/login', [LoginController::class, 'loginStudent']);
Route::post('/siswa/logout', [LoginController::class, 'logoutStudent'])->name('siswa.logout');

// Student Routes
Route::middleware(['auth:student'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/exams/{exam}', [StudentExamController::class, 'showInstruction'])->name('exams.instruction');
    Route::post('/exams/{exam}/start', [StudentExamController::class, 'startExam'])->name('exams.start');
    
    Route::get('/attempts/{attempt}', [StudentExamController::class, 'showAttempt'])->name('attempts.show');
    Route::post('/attempts/{attempt}/save-answer', [StudentExamController::class, 'saveAnswer'])->name('attempts.saveAnswer');
    Route::post('/attempts/{attempt}/violation', [StudentExamController::class, 'recordViolation'])->name('attempts.recordViolation');
    Route::post('/attempts/{attempt}/submit', [StudentExamController::class, 'submitExam'])->name('attempts.submit');
    Route::get('/attempts/{attempt}/result', [StudentExamController::class, 'showResult'])->name('attempts.result');
});

// Teacher Routes (Role: Guru or Superadmin)
Route::middleware(['auth:web'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    Route::resource('exams', TeacherExamController::class);
    Route::post('/exams/{exam}/toggle-status', [TeacherExamController::class, 'toggleStatus'])->name('exams.toggleStatus');

    Route::resource('exams.questions', QuestionController::class);

    Route::get('/exams/{exam}/participants', [ParticipantController::class, 'index'])->name('exams.participants');
    Route::get('/violations', [ParticipantController::class, 'indexViolations'])->name('violations.index');
    Route::post('/attempts/{attempt}/reset', [ParticipantController::class, 'resetAttempt'])->name('attempts.reset');

    Route::get('/results', [TeacherResultController::class, 'index'])->name('results.index');
    Route::get('/results/exam/{exam}', [TeacherResultController::class, 'showExamResult'])->name('results.exam');
});

// Superadmin Routes (Role: Superadmin only)
Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Students Management
    Route::get('/students/import', [AdminStudentController::class, 'showImportForm'])->name('students.import');
    Route::post('/students/import', [AdminStudentController::class, 'processImport']);
    Route::post('/students/{student}/reset-password', [AdminStudentController::class, 'resetPassword'])->name('students.resetPassword');
    Route::resource('students', AdminStudentController::class);

    // Teachers Management
    Route::resource('teachers', TeacherController::class);

    // Master Data Management
    Route::resource('classes', ClassController::class);
    Route::resource('majors', MajorController::class)->except(['create', 'edit', 'show']);
    
    Route::resource('academic-years', AcademicYearController::class)->only(['index', 'store']);
    Route::post('/academic-years/{academicYear}/toggle-active', [AcademicYearController::class, 'toggleActive'])->name('academic-years.toggleActive');

    Route::resource('subjects', SubjectController::class)->except(['create', 'edit', 'show']);

    // Academic Year Grade Promotion
    Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion.index');
    Route::post('/promotion', [PromotionController::class, 'process'])->name('promotion.process');

    // System Activity Audit Trail
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
});
