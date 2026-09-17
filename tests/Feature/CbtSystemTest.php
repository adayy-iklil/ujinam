<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Major;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Services\AcademicYearPromotionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CbtSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;
    protected User $guru;
    protected Student $student;
    protected Exam $exam;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic environment
        $this->superadmin = User::create([
            'name' => 'Admin Test',
            'username' => 'superadmin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        $this->guru = User::create([
            'name' => 'Guru Test',
            'username' => 'gurutest',
            'email' => 'guru@test.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        $ay = AcademicYear::create([
            'name' => '2025/2026',
            'start_year' => 2025,
            'end_year' => 2026,
            'is_active' => true,
        ]);

        $major = Major::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);

        $class = SchoolClass::create([
            'academic_year_id' => $ay->id,
            'major_id' => $major->id,
            'grade' => 'XII',
            'name' => 'XII RPL',
            'class_number' => 1,
            'is_active' => true,
        ]);

        $this->student = Student::create([
            'nis' => '1001',
            'name' => 'Budi Siswa',
            'gender' => 'L',
            'class_id' => $class->id,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $subject = Subject::create(['code' => 'WEB', 'name' => 'Pemrograman Web', 'is_active' => true]);

        $this->exam = Exam::create([
            'subject_id' => $subject->id,
            'created_by' => $this->guru->id,
            'title' => 'Ujian Harian Web',
            'duration_minutes' => 60,
            'start_at' => now()->subHour(),
            'end_at' => now()->addDay(),
            'status' => 'published',
            'show_result' => true,
            'randomize_questions' => true,
            'is_active' => true,
        ]);

        $this->exam->classes()->attach($class->id);

        // Add 2 questions
        $q1 = Question::create(['exam_id' => $this->exam->id, 'question_text' => 'Apakah HTML bahasa markah?', 'points' => 50]);
        QuestionOption::create(['question_id' => $q1->id, 'option_key' => 'A', 'option_text' => 'Ya', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q1->id, 'option_key' => 'B', 'option_text' => 'Bukan', 'is_correct' => false]);

        $q2 = Question::create(['exam_id' => $this->exam->id, 'question_text' => 'CSS singkatan dari?', 'points' => 50]);
        QuestionOption::create(['question_id' => $q2->id, 'option_key' => 'A', 'option_text' => 'Cascading Style Sheets', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q2->id, 'option_key' => 'B', 'option_text' => 'Computer Style', 'is_correct' => false]);
    }

    public function test_student_can_login_with_nis()
    {
        $response = $this->post('/siswa/login', [
            'nis' => '1001',
            'password' => 'password',
        ]);

        $response->assertRedirect('/siswa/dashboard');
        $this->assertAuthenticatedAs($this->student, 'student');
    }

    public function test_student_cannot_login_with_wrong_password()
    {
        $response = $this->post('/siswa/login', [
            'nis' => '1001',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('nis');
        $this->assertGuest('student');
    }

    public function test_student_can_start_exam_and_generate_deterministic_attempt()
    {
        $this->actingAs($this->student, 'student');

        $response = $this->post("/siswa/exams/{$this->exam->id}/start");

        $attempt = ExamAttempt::where('exam_id', $this->exam->id)->where('student_id', $this->student->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals('in_progress', $attempt->status);
        $this->assertEquals(2, $attempt->attemptQuestions()->count());

        $response->assertRedirect("/siswa/attempts/{$attempt->id}");
    }

    public function test_anti_cheat_locks_attempt_at_3_violations()
    {
        $this->actingAs($this->student, 'student');

        $attempt = ExamAttempt::create([
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'started_at' => now(),
            'expires_at' => now()->addMinutes(60),
            'status' => 'in_progress',
            'violation_count' => 0,
        ]);

        // Violation 1
        $this->postJson("/siswa/attempts/{$attempt->id}/violation", ['violation_type' => 'tab_hidden'])
            ->assertJson(['status' => 'warning', 'violation_count' => 1]);

        // Violation 2
        $this->postJson("/siswa/attempts/{$attempt->id}/violation", ['violation_type' => 'tab_hidden'])
            ->assertJson(['status' => 'warning', 'violation_count' => 2]);

        // Violation 3 -> Locked
        $this->postJson("/siswa/attempts/{$attempt->id}/violation", ['violation_type' => 'tab_hidden'])
            ->assertJson(['status' => 'locked', 'violation_count' => 3]);

        $attempt->refresh();
        $this->assertEquals('locked', $attempt->status);
    }

    public function test_server_side_scoring_calculation()
    {
        $this->actingAs($this->student, 'student');

        $attempt = ExamAttempt::create([
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'started_at' => now(),
            'expires_at' => now()->addMinutes(60),
            'status' => 'in_progress',
        ]);

        $questions = $this->exam->questions()->with('options')->get();

        // Answer Q1 correctly, Q2 wrongly
        $q1CorrectOpt = $questions[0]->options()->where('is_correct', true)->first();
        $q2WrongOpt = $questions[1]->options()->where('is_correct', false)->first();

        $this->postJson("/siswa/attempts/{$attempt->id}/save-answer", [
            'question_id' => $questions[0]->id,
            'selected_option_id' => $q1CorrectOpt->id,
        ])->assertJson(['status' => 'success']);

        $this->postJson("/siswa/attempts/{$attempt->id}/save-answer", [
            'question_id' => $questions[1]->id,
            'selected_option_id' => $q2WrongOpt->id,
        ])->assertJson(['status' => 'success']);

        // Submit exam
        $response = $this->post("/siswa/attempts/{$attempt->id}/submit");
        $response->assertRedirect("/siswa/attempts/{$attempt->id}/result");

        $attempt->refresh();
        $this->assertEquals('submitted', $attempt->status);
        $this->assertEquals(50, $attempt->score);
        $this->assertEquals(1, $attempt->correct_answers);
        $this->assertEquals(1, $attempt->wrong_answers);

        // Verify result view renders
        $resultRes = $this->get("/siswa/attempts/{$attempt->id}/result");
        $resultRes->assertStatus(200);
        $resultRes->assertSee('Nilai Akhir Ujian');
        $resultRes->assertSee('50');
    }

    public function test_student_answers_payload_fallback_on_submission_grades_correctly()
    {
        $this->actingAs($this->student, 'student');

        $attempt = ExamAttempt::create([
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'started_at' => now(),
            'expires_at' => now()->addMinutes(60),
            'status' => 'in_progress',
        ]);

        $questions = $this->exam->questions()->with('options')->get();
        $q1CorrectOpt = $questions[0]->options()->where('is_correct', true)->first();
        $q2CorrectOpt = $questions[1]->options()->where('is_correct', true)->first();

        // Submit directly with answers_payload (simulating when autosave was bypassed or offline)
        $response = $this->post("/siswa/attempts/{$attempt->id}/submit", [
            'answers_payload' => json_encode([
                $questions[0]->id => $q1CorrectOpt->id,
                $questions[1]->id => $q2CorrectOpt->id,
            ]),
        ]);

        $response->assertRedirect("/siswa/attempts/{$attempt->id}/result");

        $attempt->refresh();
        $this->assertEquals('submitted', $attempt->status);
        $this->assertEquals(100, $attempt->score);
        $this->assertEquals(2, $attempt->correct_answers);
        $this->assertEquals(0, $attempt->wrong_answers);

        // Verify result view renders 100
        $resultRes = $this->get("/siswa/attempts/{$attempt->id}/result");
        $resultRes->assertStatus(200);
        $resultRes->assertSee('100');
        $resultRes->assertSee('TUNTAS');
    }

    public function test_teacher_can_unlock_and_reset_locked_student()
    {
        $attempt = ExamAttempt::create([
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'started_at' => now(),
            'expires_at' => now()->addMinutes(60),
            'status' => 'locked',
            'violation_count' => 3,
        ]);

        $this->actingAs($this->guru, 'web');

        $response = $this->post("/guru/attempts/{$attempt->id}/reset", ['action_type' => 'unlock']);
        $response->assertSessionHas('success');

        $attempt->refresh();
        $this->assertEquals('in_progress', $attempt->status);
        $this->assertEquals(0, $attempt->violation_count);
    }

    public function test_academic_year_promotion_service()
    {
        $ayCurrent = AcademicYear::where('is_active', true)->first();
        $ayNext = AcademicYear::create([
            'name' => '2026/2027',
            'start_year' => 2026,
            'end_year' => 2027,
            'is_active' => false,
        ]);

        // Create Class XI student
        $classXI = SchoolClass::create([
            'academic_year_id' => $ayCurrent->id,
            'major_id' => Major::first()->id,
            'grade' => 'XI',
            'name' => 'XI RPL',
            'class_number' => 1,
            'is_active' => true,
        ]);

        $studentXI = Student::create([
            'nis' => '3001',
            'name' => 'Siswa XI',
            'gender' => 'P',
            'class_id' => $classXI->id,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $promotionService = new AcademicYearPromotionService();
        $res = $promotionService->promote($ayCurrent->id, $ayNext->id);

        $this->assertEquals(1, $res['promoted_students']);

        $studentXI->refresh();
        $this->assertEquals('XII', $studentXI->schoolClass->grade);
        $this->assertEquals($ayNext->id, $studentXI->schoolClass->academic_year_id);
    }
}
