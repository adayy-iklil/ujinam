# Ujinam — Cara Kerja Sistem CBT Ujian Sekolah

> **Versi:** 1.0 | **Stack:** Laravel 11 · MySQL · Blade · Tailwind CSS · Alpine.js

---

## Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Role Pengguna](#role-pengguna)
3. [Autentikasi & Guard](#autentikasi--guard)
4. [Alur Ujian Siswa](#alur-ujian-siswa)
5. [Fitur Guru](#fitur-guru)
6. [Fitur Superadmin](#fitur-superadmin)
7. [Monitoring Pelanggaran](#monitoring-pelanggaran)
8. [Skema Database](#skema-database)
9. [Cara Instalasi](#cara-instalasi)
10. [Akun Default](#akun-default)
11. [URL Penting](#url-penting)

---

## Gambaran Umum

**Ujinam** adalah platform CBT (Computer Based Test) untuk ujian sekolah internal.
Sistem ini memungkinkan:

- **Guru** membuat soal, menjadwalkan ujian, memantau peserta secara real-time
- **Siswa** mengerjakan ujian langsung di browser dengan pengawasan anti-kecurangan
- **Superadmin** mengelola seluruh data master (kelas, siswa, guru, jurusan, tahun ajaran)

```
Browser Siswa --> /siswa/login --> Jawab Soal --> Submit --> Nilai
Browser Guru  --> /login       --> Buat Ujian  --> Monitor --> Rekap Nilai
```

---

## Role Pengguna

| Role         | Guard    | Login via          | Kemampuan utama                                     |
|--------------|----------|--------------------|-----------------------------------------------------|
| `superadmin` | `web`    | `/login`           | Semua fitur admin + manajemen guru                  |
| `guru`       | `web`    | `/login`           | Buat/kelola ujian dan soal di mata pelajaran sendiri|
| `siswa`      | `student`| `/siswa/login`     | Lihat & kerjakan ujian yang ditugaskan ke kelasnya  |

---

## Autentikasi & Guard

Sistem menggunakan **dua Laravel Auth Guard**:

### Guard `web` — Guru & Superadmin
- Login dengan **username** (bukan email) + password
- Route: `POST /login`
- Session key: `web`

### Guard `student` — Siswa
- Login dengan **NIS** (Nomor Induk Siswa) + password
- Route: `POST /siswa/login`
- Session key: `student`
- Model: `App\Models\Student` (terpisah dari `User`)

---

## Alur Ujian Siswa

```
1. LOGIN (/siswa/login)
   Masukkan NIS + Password

2. DASHBOARD (/siswa/dashboard)
   Lihat daftar ujian untuk kelasnya
   Status: Belum Mulai / Sedang / Selesai / Terkunci

3. INSTRUKSI (/siswa/exams/{exam})
   Baca petunjuk, klik [Mulai Ujian]

4. START (POST /siswa/exams/{exam}/start)
   Sistem buat ExamAttempt, soal di-generate
   Timer dimulai dari server (expires_at)

5. KERJAKAN (/siswa/attempts/{attempt})
   Jawab soal, simpan otomatis via AJAX
   Timer countdown di pojok kanan atas
   Alpine.js pantau: tab-switch, blur, fullscreen

6. SIMPAN JAWABAN (POST save-answer)
   Upsert ke student_answers

7. PELANGGARAN (POST violation)
   violation_count++
   Jika >= 3: status = force_logged_out, siswa dikunci

8. SUBMIT (POST submit)
   Hitung skor, update status = submitted

9. HASIL (/siswa/attempts/{attempt}/result)
   Tampilkan nilai, benar/salah
```

---

## Fitur Guru

### Manajemen Ujian `/guru/exams`
- Buat ujian: pilih mapel, kelas, jadwal, durasi, acak soal/jawaban
- Ubah status: draft -> published -> ongoing -> finished

### Manajemen Soal `/guru/exams/{exam}/questions`
- Tambah/edit/hapus soal
- 5 pilihan (A-E) per soal, 1 jawaban benar, nilai per soal

### Monitor Peserta `/guru/exams/{exam}/participants`
- Status real-time tiap siswa
- Jumlah pelanggaran
- Reset akses siswa yang terkunci

### Rekap Nilai `/guru/results/exam/{exam}`
- Tabel nilai semua peserta

---

## Fitur Superadmin

| Fitur                | URL                        |
|----------------------|----------------------------|
| Tahun Ajaran         | `/admin/academic-years`    |
| Jurusan              | `/admin/majors`            |
| Kelas                | `/admin/classes`           |
| Mata Pelajaran       | `/admin/subjects`          |
| Guru                 | `/admin/teachers`          |
| Siswa                | `/admin/students`          |
| Import Siswa (CSV)   | `/admin/students/import`   |
| Kenaikan Kelas       | `/admin/promotion`         |
| Audit Log            | `/admin/logs`              |

### Format CSV Import Siswa
```
nis,name,gender,class_name
2401001,Ahmad Fauzi,L,XI RPL
2401002,Citra Dewi,P,XI RPL
```

### Kenaikan Kelas
- XI -> XII: siswa dipindah ke kelas XII baru
- XII -> Lulus: siswa diarsip (is_active=false, archived_at=now)
- Data ujian & nilai lama tetap tersimpan

---

## Monitoring Pelanggaran

| Event JS               | Tipe                | Keterangan                        |
|------------------------|---------------------|-----------------------------------|
| visibilitychange       | `tab_hidden`        | Berpindah tab / minimize jendela  |
| window.blur            | `window_blur`       | Klik keluar dari jendela ujian    |
| fullscreenchange       | `fullscreen_exit`   | Keluar dari fullscreen            |
| Tombol logout          | `manual_logout`     | Keluar manual saat ujian          |

Threshold default: **3 pelanggaran** = terkunci otomatis.

---

## Skema Database

```
academic_years
    |-- classes --------- majors
          |-- students
                |-- exam_attempts --- exams --- subjects --- users
                      |-- exam_attempt_questions --- questions --- question_options
                      |-- student_answers
                      |-- exam_violations
```

### Tabel (19 total)

| Tabel                    | Fungsi                                         |
|--------------------------|------------------------------------------------|
| sessions                 | Laravel session store                          |
| cache / cache_locks      | Laravel cache store                            |
| jobs / job_batches       | Laravel queue                                  |
| failed_jobs              | Queue job failures                             |
| users                    | Akun Guru dan Superadmin                       |
| academic_years           | Tahun Ajaran (2025/2026, 2026/2027, dll)       |
| majors                   | Jurusan (RPL, DKV, AK, dll)                   |
| classes                  | Kelas (XI RPL, XII AK 1, dll)                 |
| students                 | Akun Siswa dengan NIS                          |
| subjects                 | Mata Pelajaran                                 |
| teacher_subjects         | Pivot: Guru <-> Mapel                          |
| exams                    | Ujian (judul, jadwal, durasi, status)          |
| exam_classes             | Pivot: Ujian <-> Kelas                         |
| questions                | Soal per Ujian                                 |
| question_options         | Pilihan A-E per Soal                           |
| exam_attempts            | Sesi pengerjaan per Siswa                      |
| exam_attempt_questions   | Urutan soal per Siswa (randomisasi disimpan)   |
| student_answers          | Jawaban Siswa (upsert)                         |
| exam_violations          | Log pelanggaran                                |
| activity_logs            | Audit trail semua aksi                         |

---

## Cara Instalasi

### Prasyarat
- PHP >= 8.2 + ekstensi: pdo_mysql, mbstring, openssl
- Composer >= 2.x
- MySQL 5.7+ / MariaDB 10.3+
- Node.js >= 18 + npm

### Langkah-langkah

```bash
# 1. Masuk ke folder project
cd c:/xampp/htdocs/ujinam

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Konfigurasi environment
copy .env.example .env
php artisan key:generate
```

Edit `.env`:
```dotenv
APP_NAME=Ujinam
DB_DATABASE=ujinam_db
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4a. Via Laravel (buat tabel + seed data)
php artisan migrate --seed

# ATAU 4b. Via SQL langsung
# Import file: database/ujinam_db.sql ke phpMyAdmin

# 5. Jalankan server
php artisan serve
# Buka: http://localhost:8000
```

---

## Akun Default

> Password semua akun: **`password`**

| Peran        | Login di       | Kredensial                       |
|--------------|----------------|----------------------------------|
| Superadmin   | `/login`       | username: `superadmin`           |
| Guru Budi    | `/login`       | username: `budi`                 |
| Guru Siti    | `/login`       | username: `siti`                 |
| Siswa XI RPL | `/siswa/login` | NIS: `2401001` s/d `2401005`     |
| Siswa XII RPL| `/siswa/login` | NIS: `2301001` s/d `2301003`     |

> PENTING: Ganti semua password default sebelum digunakan di produksi!

---

## URL Penting

| Halaman                  | URL                                    | Akses      |
|--------------------------|----------------------------------------|------------|
| Redirect utama           | `/`                                    | Semua      |
| Login Siswa              | `/siswa/login`                         | Publik     |
| Login Guru/Admin         | `/login`                               | Publik     |
| Dashboard Siswa          | `/siswa/dashboard`                     | Siswa      |
| Kerjakan Ujian           | `/siswa/attempts/{attempt}`            | Siswa      |
| Hasil Ujian              | `/siswa/attempts/{attempt}/result`     | Siswa      |
| Dashboard Guru           | `/guru/dashboard`                      | Guru       |
| Buat Ujian               | `/guru/exams/create`                   | Guru       |
| Soal Ujian               | `/guru/exams/{exam}/questions`         | Guru       |
| Monitor Peserta          | `/guru/exams/{exam}/participants`      | Guru       |
| Dashboard Admin          | `/admin/dashboard`                     | Superadmin |
| Manajemen Siswa          | `/admin/students`                      | Superadmin |
| Import Siswa             | `/admin/students/import`               | Superadmin |
| Kenaikan Kelas           | `/admin/promotion`                     | Superadmin |
| Audit Log                | `/admin/logs`                          | Superadmin |

---

*Dokumentasi Sistem CBT Ujinam v1.0*
