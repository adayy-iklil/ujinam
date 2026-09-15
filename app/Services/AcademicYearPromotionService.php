<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AcademicYearPromotionService
{
    /**
     * Promote active students from current academic year to target academic year.
     * XI -> XII
     * X -> Archive/Inactive
     */
    public function promote(int $fromAcademicYearId, int $toAcademicYearId): array
    {
        return DB::transaction(function () use ($fromAcademicYearId, $toAcademicYearId) {
            $fromAy = AcademicYear::findOrFail($fromAcademicYearId);
            $toAy = AcademicYear::findOrFail($toAcademicYearId);

            $promotedCount = 0;
            $archivedCount = 0;
            $logs = [];

            // 1. Archive Class X students from current academic year
            $classXIds = SchoolClass::where('academic_year_id', $fromAcademicYearId)
                ->where('grade', 'X')
                ->pluck('id');

            $classXStudents = Student::whereIn('class_id', $classXIds)->where('is_active', true)->get();
            foreach ($classXStudents as $student) {
                $student->update([
                    'is_active' => false,
                    'archived_at' => now(),
                ]);
                $archivedCount++;
            }

            // 2. Promote Class XI students to Class XII in target academic year
            $classXIList = SchoolClass::where('academic_year_id', $fromAcademicYearId)
                ->where('grade', 'XI')
                ->get();

            foreach ($classXIList as $oldClass) {
                // Find or create matching Class XII in target academic year
                $targetClass = SchoolClass::firstOrCreate(
                    [
                        'academic_year_id' => $toAcademicYearId,
                        'major_id' => $oldClass->major_id,
                        'grade' => 'XII',
                        'class_number' => $oldClass->class_number,
                    ],
                    [
                        'name' => 'XII ' . ($oldClass->major ? $oldClass->major->code : '') . ($oldClass->class_number > 1 ? ' ' . $oldClass->class_number : ''),
                        'is_active' => true,
                    ]
                );

                $students = Student::where('class_id', $oldClass->id)->where('is_active', true)->get();
                foreach ($students as $student) {
                    $student->update([
                        'class_id' => $targetClass->id,
                    ]);
                    $promotedCount++;
                }

                $logs[] = "Kelas {$oldClass->name} ({$students->count()} siswa) dipromosikan ke {$targetClass->name}.";
            }

            // 3. Switch active academic year
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
            $toAy->update(['is_active' => true]);

            // Log activity
            ActivityLog::record(
                'PROMOTION_EXECUTED',
                "Kenaikan kelas berhasil dijalankan dari tahun ajaran {$fromAy->name} ke {$toAy->name}. Dipromosikan: {$promotedCount}, Diarsip: {$archivedCount}."
            );

            return [
                'promoted_students' => $promotedCount,
                'archived_students' => $archivedCount,
                'details' => $logs,
            ];
        });
    }
}
