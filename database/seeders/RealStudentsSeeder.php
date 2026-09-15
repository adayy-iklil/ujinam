<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * RealStudentsSeeder
 *
 * Seeds a realistic set of students across several classes.
 * Called by DatabaseSeeder after classes are created.
 *
 * Default password for all students: "password"
 */
class RealStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Cari kelas berdasarkan nama (dibuat oleh DatabaseSeeder)
        $classMap = SchoolClass::pluck('id', 'name')->toArray();

        $students = [
            // ── XI RPL ──────────────────────────────────────────────────────
            ['nis' => '2401001', 'name' => 'Ahmad Fauzi',          'gender' => 'L', 'class' => 'XI RPL'],
            ['nis' => '2401002', 'name' => 'Bagas Prasetyo',       'gender' => 'L', 'class' => 'XI RPL'],
            ['nis' => '2401003', 'name' => 'Citra Dewi',           'gender' => 'P', 'class' => 'XI RPL'],
            ['nis' => '2401004', 'name' => 'Dian Rahayu',          'gender' => 'P', 'class' => 'XI RPL'],
            ['nis' => '2401005', 'name' => 'Eko Widodo',           'gender' => 'L', 'class' => 'XI RPL'],

            // ── XII RPL ──────────────────────────────────────────────────────
            ['nis' => '2301001', 'name' => 'Fajar Nugroho',        'gender' => 'L', 'class' => 'XII RPL'],
            ['nis' => '2301002', 'name' => 'Galuh Permata',        'gender' => 'P', 'class' => 'XII RPL'],
            ['nis' => '2301003', 'name' => 'Hendra Kusuma',        'gender' => 'L', 'class' => 'XII RPL'],

            // ── XI AK 1 ──────────────────────────────────────────────────────
            ['nis' => '2402001', 'name' => 'Indah Lestari',        'gender' => 'P', 'class' => 'XI AK 1'],
            ['nis' => '2402002', 'name' => 'Joko Santoso',         'gender' => 'L', 'class' => 'XI AK 1'],
            ['nis' => '2402003', 'name' => 'Kayla Putri',          'gender' => 'P', 'class' => 'XI AK 1'],

            // ── XII AK 1 ──────────────────────────────────────────────────────
            ['nis' => '2302001', 'name' => 'Kartika Sari',         'gender' => 'P', 'class' => 'XII AK 1'],
            ['nis' => '2302002', 'name' => 'Lukman Hakim',         'gender' => 'L', 'class' => 'XII AK 1'],

            // ── XI DKV 1 ──────────────────────────────────────────────────────
            ['nis' => '2403001', 'name' => 'Maya Putri',           'gender' => 'P', 'class' => 'XI DKV 1'],
            ['nis' => '2403002', 'name' => 'Nando Saputra',        'gender' => 'L', 'class' => 'XI DKV 1'],

            // ── XII MP ──────────────────────────────────────────────────────
            ['nis' => '2304001', 'name' => 'Olivia Maharani',      'gender' => 'P', 'class' => 'XII MP'],
            ['nis' => '2304002', 'name' => 'Panji Setiawan',       'gender' => 'L', 'class' => 'XII MP'],

            // ── XI BR 1 ──────────────────────────────────────────────────────
            ['nis' => '2405001', 'name' => 'Qonita Azzahra',       'gender' => 'P', 'class' => 'XI BR 1'],
            ['nis' => '2405002', 'name' => 'Rafi Izzudin',         'gender' => 'L', 'class' => 'XI BR 1'],
        ];

        $password = Hash::make('password');

        foreach ($students as $data) {
            $classId = $classMap[$data['class']] ?? null;

            if (!$classId) {
                $this->command->warn("Kelas [{$data['class']}] tidak ditemukan, siswa [{$data['name']}] dilewati.");
                continue;
            }

            Student::firstOrCreate(
                ['nis' => $data['nis']],
                [
                    'name'      => $data['name'],
                    'gender'    => $data['gender'],
                    'class_id'  => $classId,
                    'password'  => $password,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('RealStudentsSeeder: ' . count($students) . ' siswa berhasil di-seed.');
    }
}
