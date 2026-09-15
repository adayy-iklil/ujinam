<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        $studentClass = $student->schoolClass;

        if (!$studentClass) {
            return view('student.dashboard', [
                'student' => $student,
                'availableExams' => collect(),
                'attempts' => collect(),
            ]);
        }

        // Get exams assigned to student's class
        $availableExams = Exam::whereHas('classes', function ($q) use ($studentClass) {
            $q->where('classes.id', $studentClass->id);
        })
        ->where('is_active', true)
        ->whereIn('status', ['published', 'ongoing'])
        ->with(['subject'])
        ->orderBy('start_at', 'asc')
        ->get();

        // Get all attempts by student
        $attempts = ExamAttempt::where('student_id', $student->id)
            ->get()
            ->keyBy('exam_id');

        return view('student.dashboard', compact('student', 'availableExams', 'attempts'));
    }
}
