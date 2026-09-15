<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::where('is_active', true)->count();
        $totalTeachers = User::where('role', 'guru')->where('is_active', true)->count();
        $totalClasses = SchoolClass::where('is_active', true)->count();
        $activeExams = Exam::whereIn('status', ['published', 'ongoing'])->where('is_active', true)->count();
        $lockedStudents = ExamAttempt::where('status', 'locked')->orWhere('violation_count', '>=', 3)->count();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        $recentExams = Exam::with(['subject', 'creator'])
            ->withCount('attempts')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'activeExams',
            'lockedStudents',
            'activeAcademicYear',
            'recentExams'
        ));
    }
}
