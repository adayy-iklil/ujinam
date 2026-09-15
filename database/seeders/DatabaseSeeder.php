<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Major;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Superadmin & Teachers)
        $superadmin = User::create([
            'name' => 'Administrator System',
            'username' => 'superadmin',
            'email' => 'admin@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        $guru1 = User::create([
            'name' => 'Budi Santoso, S.Kom',
            'username' => 'budi',
            'email' => 'budi@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        $guru2 = User::create([
            'name' => 'Siti Aminah, M.Pd',
            'username' => 'siti',
            'email' => 'siti@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        // 2. Academic Years
        $ayCurrent = AcademicYear::create([
            'name' => '2025/2026',
            'start_year' => 2025,
            'end_year' => 2026,
            'is_active' => true,
        ]);

        $ayNext = AcademicYear::create([
            'name' => '2026/2027',
            'start_year' => 2026,
            'end_year' => 2027,
            'is_active' => false,
        ]);

        // 3. Majors
        $mp = Major::create(['code' => 'MP', 'name' => 'Manajemen Perkantoran']);
        $ak = Major::create(['code' => 'AK', 'name' => 'Akuntansi dan Keuangan']);
        $br = Major::create(['code' => 'BR', 'name' => 'Bisnis Retail']);
        $dkv = Major::create(['code' => 'DKV', 'name' => 'Desain Komunikasi Visual']);
        $an = Major::create(['code' => 'AN', 'name' => 'Animasi']);
        $rpl = Major::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);

        // 4. Subjects
        $subjWeb = Subject::create(['code' => 'PWPB', 'name' => 'Pemrograman Web dan Perangkat Bergerak', 'description' => 'Kejuruan RPL', 'is_active' => true]);
        $subjDB = Subject::create(['code' => 'BD', 'name' => 'Basis Data', 'description' => 'Kejuruan RPL', 'is_active' => true]);
        $subjIndo = Subject::create(['code' => 'BINDO', 'name' => 'Bahasa Indonesia', 'description' => 'Wajib Nasional', 'is_active' => true]);

        $guru1->subjects()->attach([$subjWeb->id, $subjDB->id]);
        $guru2->subjects()->attach([$subjIndo->id]);

        // 5. Create Classes for Grade XI and Grade XII
        $classes = [
            'XI MP' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $mp->id, 'grade' => 'XI', 'name' => 'XI MP', 'class_number' => 1, 'is_active' => true]),
            'XI AK 1' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $ak->id, 'grade' => 'XI', 'name' => 'XI AK 1', 'class_number' => 1, 'is_active' => true]),
            'XI AK 2' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $ak->id, 'grade' => 'XI', 'name' => 'XI AK 2', 'class_number' => 2, 'is_active' => true]),
            'XI BR 1' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $br->id, 'grade' => 'XI', 'name' => 'XI BR 1', 'class_number' => 1, 'is_active' => true]),
            'XI BR 2' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $br->id, 'grade' => 'XI', 'name' => 'XI BR 2', 'class_number' => 2, 'is_active' => true]),
            'XI DKV 1' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $dkv->id, 'grade' => 'XI', 'name' => 'XI DKV 1', 'class_number' => 1, 'is_active' => true]),
            'XI DKV 2' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $dkv->id, 'grade' => 'XI', 'name' => 'XI DKV 2', 'class_number' => 2, 'is_active' => true]),
            'XI AN' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $an->id, 'grade' => 'XI', 'name' => 'XI AN', 'class_number' => 1, 'is_active' => true]),
            'XI RPL' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $rpl->id, 'grade' => 'XI', 'name' => 'XI RPL', 'class_number' => 1, 'is_active' => true]),

            'XII MP' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $mp->id, 'grade' => 'XII', 'name' => 'XII MP', 'class_number' => 1, 'is_active' => true]),
            'XII AK 1' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $ak->id, 'grade' => 'XII', 'name' => 'XII AK 1', 'class_number' => 1, 'is_active' => true]),
            'XII AK 2' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $ak->id, 'grade' => 'XII', 'name' => 'XII AK 2', 'class_number' => 2, 'is_active' => true]),
            'XII BR 1' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $br->id, 'grade' => 'XII', 'name' => 'XII BR 1', 'class_number' => 1, 'is_active' => true]),
            'XII BR 2' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $br->id, 'grade' => 'XII', 'name' => 'XII BR 2', 'class_number' => 2, 'is_active' => true]),
            'XII DKV 1' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $dkv->id, 'grade' => 'XII', 'name' => 'XII DKV 1', 'class_number' => 1, 'is_active' => true]),
            'XII DKV 2' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $dkv->id, 'grade' => 'XII', 'name' => 'XII DKV 2', 'class_number' => 2, 'is_active' => true]),
            'XII AN' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $an->id, 'grade' => 'XII', 'name' => 'XII AN', 'class_number' => 1, 'is_active' => true]),
            'XII RPL' => SchoolClass::create(['academic_year_id' => $ayCurrent->id, 'major_id' => $rpl->id, 'grade' => 'XII', 'name' => 'XII RPL', 'class_number' => 1, 'is_active' => true]),
        ];

        // Seed Sample Exam
        $exam1 = Exam::create([
            'subject_id' => $subjWeb->id,
            'created_by' => $guru1->id,
            'title' => 'Penilaian Akhir Semester - Pemrograman Web',
            'description' => 'Ujian Akhir Semester Gasal Tahun Ajaran 2025/2026',
            'instructions' => "1. Kerjakan soal dengan teliti.\n2. Waktu pengerjaan 60 menit.\n3. Jangan berpindah tab selama ujian berlangsung.",
            'duration_minutes' => 60,
            'start_at' => now()->subHours(1),
            'end_at' => now()->addDays(7),
            'status' => 'published',
            'show_result' => true,
            'randomize_questions' => true,
            'randomize_options' => true,
            'is_active' => true,
        ]);

        $exam1->classes()->attach([$classes['XI RPL']->id, $classes['XII RPL']->id]);

        $questionsData = [
            ['text' => 'Elemen HTML manakah yang digunakan untuk mendefinisikan judul tingkat pertama?', 'points' => 20, 'options' => [['key' => 'A', 'text' => '<h1>', 'is_correct' => true], ['key' => 'B', 'text' => '<head>', 'is_correct' => false], ['key' => 'C', 'text' => '<title>', 'is_correct' => false], ['key' => 'D', 'text' => '<h6>', 'is_correct' => false], ['key' => 'E', 'text' => '<header>', 'is_correct' => false]]],
            ['text' => 'Properti CSS apa yang digunakan untuk mengubah warna latar belakang sebuah elemen?', 'points' => 20, 'options' => [['key' => 'A', 'text' => 'color', 'is_correct' => false], ['key' => 'B', 'text' => 'background-color', 'is_correct' => true], ['key' => 'C', 'text' => 'bg-style', 'is_correct' => false], ['key' => 'D', 'text' => 'bgcolor', 'is_correct' => false], ['key' => 'E', 'text' => 'border-color', 'is_correct' => false]]],
            ['text' => 'Di bawah ini yang merupakan framework PHP berbasis arsitektur MVC adalah...', 'points' => 20, 'options' => [['key' => 'A', 'text' => 'React', 'is_correct' => false], ['key' => 'B', 'text' => 'Laravel', 'is_correct' => true], ['key' => 'C', 'text' => 'Express.js', 'is_correct' => false], ['key' => 'D', 'text' => 'Django', 'is_correct' => false], ['key' => 'E', 'text' => 'Spring Boot', 'is_correct' => false]]],
            ['text' => 'Perintah SQL yang digunakan untuk mengambil data dari tabel basis data relasional adalah...', 'points' => 20, 'options' => [['key' => 'A', 'text' => 'INSERT', 'is_correct' => false], ['key' => 'B', 'text' => 'UPDATE', 'is_correct' => false], ['key' => 'C', 'text' => 'DELETE', 'is_correct' => false], ['key' => 'D', 'text' => 'SELECT', 'is_correct' => true], ['key' => 'E', 'text' => 'ALTER', 'is_correct' => false]]],
            ['text' => 'Method HTTP yang umumnya digunakan untuk mengirim data formulir secara aman adalah...', 'points' => 20, 'options' => [['key' => 'A', 'text' => 'GET', 'is_correct' => false], ['key' => 'B', 'text' => 'POST', 'is_correct' => true], ['key' => 'C', 'text' => 'HEAD', 'is_correct' => false], ['key' => 'D', 'text' => 'OPTIONS', 'is_correct' => false], ['key' => 'E', 'text' => 'TRACE', 'is_correct' => false]]],
        ];

        foreach ($questionsData as $idx => $qData) {
            $question = Question::create(['exam_id' => $exam1->id, 'question_text' => $qData['text'], 'question_type' => 'multiple_choice', 'points' => $qData['points'], 'question_order' => $idx + 1]);
            foreach ($qData['options'] as $optIdx => $optData) {
                QuestionOption::create(['question_id' => $question->id, 'option_key' => $optData['key'], 'option_text' => $optData['text'], 'is_correct' => $optData['is_correct'], 'option_order' => $optIdx + 1]);
            }
        }

        // 6. Call Real Students Seeder
        $this->call(RealStudentsSeeder::class);
    }
}
