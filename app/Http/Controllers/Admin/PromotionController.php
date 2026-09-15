<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Services\AcademicYearPromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $currentAy = AcademicYear::where('is_active', true)->first();

        return view('admin.promotion.index', compact('academicYears', 'currentAy'));
    }

    public function process(Request $request, AcademicYearPromotionService $promotionService)
    {
        $validated = $request->validate([
            'from_academic_year_id' => 'required|exists:academic_years,id',
            'to_academic_year_id' => 'required|exists:academic_years,id|different:from_academic_year_id',
        ], [
            'to_academic_year_id.different' => 'Tahun ajaran tujuan harus berbeda dengan tahun ajaran asal.',
        ]);

        $result = $promotionService->promote(
            $validated['from_academic_year_id'],
            $validated['to_academic_year_id']
        );

        return view('admin.promotion.result', compact('result'));
    }
}
