<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentImportService
{
    /**
     * Process array of student rows.
     * Each row expects keys: nis, name, gender (L/P), class_name (e.g., "XI RPL" or "RPL"), password
     */
    public function import(array $rows, int $defaultClassId = null): array
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 1;

            $validator = Validator::make($row, [
                'nis' => 'required|string|distinct|unique:students,nis',
                'name' => 'required|string|max:255',
                'gender' => 'required|in:L,P',
                'password' => 'required|string|min:4',
            ], [
                'nis.required' => "Baris {$rowNum}: NIS tidak boleh kosong.",
                'nis.unique' => "Baris {$rowNum}: NIS '{$row['nis']}' sudah terdaftar.",
                'name.required' => "Baris {$rowNum}: Nama siswa tidak boleh kosong.",
                'gender.in' => "Baris {$rowNum}: Jenis kelamin harus L atau P.",
                'password.required' => "Baris {$rowNum}: Password tidak boleh kosong.",
            ]);

            if ($validator->fails()) {
                $failedCount++;
                foreach ($validator->errors()->all() as $msg) {
                    $errors[] = $msg;
                }
                continue;
            }

            // Determine class ID
            $classId = $defaultClassId;
            if (!empty($row['class_id'])) {
                $classId = $row['class_id'];
            } elseif (!empty($row['class_name'])) {
                $targetClass = SchoolClass::where('name', 'LIKE', '%' . trim($row['class_name']) . '%')->first();
                if ($targetClass) {
                    $classId = $targetClass->id;
                }
            }

            if (!$classId) {
                $failedCount++;
                $errors[] = "Baris {$rowNum}: Kelas '{$row['class_name']}' tidak ditemukan.";
                continue;
            }

            try {
                Student::create([
                    'nis' => trim((string)$row['nis']),
                    'name' => trim($row['name']),
                    'gender' => strtoupper(trim($row['gender'])),
                    'class_id' => $classId,
                    'password' => Hash::make(trim($row['password'])),
                    'is_active' => true,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Baris {$rowNum}: Gagal menyimpan - " . $e->getMessage();
            }
        }

        return [
            'success' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors,
        ];
    }
}
