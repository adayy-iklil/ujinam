<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian — {{ $attempt->exam->title }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; user-select: none; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen flex flex-col" x-data="examEngine()" @save-answer.window="saveAnswer($event.detail.questionId, $event.detail.optionId)">

    <!-- CBT Minimal Exam Top Header -->
    <header class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-30 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center space-x-3 truncate">
                    <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-8 w-auto flex-shrink-0">
                    <h1 class="font-semibold text-sm sm:text-base tracking-tight truncate">{{ $attempt->exam->title }}</h1>
                </div>

                <!-- Timer & Autosave Status -->
                <div class="flex items-center space-x-4 flex-shrink-0">
                    <!-- Autosave Indicator -->
                    <div class="text-xs flex items-center space-x-1.5 px-2.5 py-1 rounded bg-slate-800 border border-slate-700">
                        <span class="w-2 h-2 rounded-full" :class="isSaving ? 'bg-amber-400 animate-pulse' : 'bg-emerald-400'"></span>
                        <span class="text-slate-300 font-medium" x-text="saveStatus">Tersimpan</span>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="bg-slate-800 border border-slate-700 px-3 py-1 rounded text-center min-w-[110px]">
                        <div class="text-[10px] text-slate-400 font-bold uppercase leading-none">Sisa Waktu</div>
                        <div class="text-base font-mono font-bold tracking-wider" :class="remainingSeconds < 300 ? 'text-rose-400 animate-pulse' : 'text-emerald-400'" x-text="formattedTimer">
                            00:00:00
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Exam Body -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </div>

    <!-- Violation Modal -->
    <div x-show="showViolationModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 max-w-md w-full p-6 text-center">
            <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-xl">
                !
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-2" x-text="violationTitle">Peringatan Pelanggaran</h3>
            <p class="text-sm text-slate-600 mb-6 leading-relaxed" x-text="violationMessage"></p>

            <template x-if="violationCount < 3">
                <button type="button" @click="closeViolationModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 px-4 rounded-md transition shadow-sm">
                    Saya Mengerti, Lanjutkan Ujian
                </button>
            </template>

            <template x-if="violationCount >= 3">
                <a href="{{ route('siswa.dashboard') }}" class="w-full block bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm py-2.5 px-4 rounded-md transition shadow-sm">
                    Kembali ke Dashboard
                </a>
            </template>
        </div>
    </div>

    <!-- Exam Engine JavaScript Logic -->
    <script>
        function examEngine() {
            return {
                expiresAtMs: {{ $attempt->expires_at->timestamp * 1000 }},
                remainingSeconds: 0,
                timerInterval: null,
                isSaving: false,
                saveStatus: 'Tersimpan',
                showViolationModal: false,
                violationCount: {{ $attempt->violation_count }},
                violationTitle: '',
                violationMessage: '',

                init() {
                    window.cbtSaveAnswer = (questionId, optionId) => {
                        this.saveAnswer(questionId, optionId);
                    };

                    this.updateTimer();
                    this.timerInterval = setInterval(() => this.updateTimer(), 1000);

                    // Check initial violation state
                    if (this.violationCount >= 3) {
                        this.handleLock();
                    }

                    // Register anti tab-switching visibility listener
                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'hidden') {
                            this.recordViolation('tab_hidden');
                        }
                    });
                },

                updateTimer() {
                    const now = new Date().getTime();
                    const diff = Math.floor((this.expiresAtMs - now) / 1000);
                    this.remainingSeconds = diff > 0 ? diff : 0;

                    if (this.remainingSeconds <= 0) {
                        clearInterval(this.timerInterval);
                        this.autoSubmit();
                    }
                },

                get formattedTimer() {
                    const hours = Math.floor(this.remainingSeconds / 3600);
                    const minutes = Math.floor((this.remainingSeconds % 3600) / 60);
                    const seconds = this.remainingSeconds % 60;

                    const pad = (n) => n < 10 ? '0' + n : n;
                    if (hours > 0) {
                        return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                    }
                    return `${pad(minutes)}:${pad(seconds)}`;
                },

                saveAnswer(questionId, optionId) {
                    this.isSaving = true;
                    this.saveStatus = 'Menyimpan...';

                    fetch('{{ route("siswa.attempts.saveAnswer", $attempt->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            selected_option_id: optionId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isSaving = false;
                        if (data.status === 'success') {
                            this.saveStatus = 'Tersimpan';
                        } else {
                            this.saveStatus = 'Gagal menyimpan';
                        }
                    })
                    .catch(err => {
                        this.isSaving = false;
                        this.saveStatus = 'Koneksi terganggu';
                    });
                },

                recordViolation(type) {
                    fetch('{{ route("siswa.attempts.recordViolation", $attempt->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ violation_type: type })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.violationCount = data.violation_count;
                        if (data.status === 'locked' || this.violationCount >= 3) {
                            this.handleLock();
                        } else {
                            this.showWarningModal();
                        }
                    });
                },

                showWarningModal() {
                    if (this.violationCount === 1) {
                        this.violationTitle = 'Peringatan 1 dari 3';
                        this.violationMessage = 'Anda terdeteksi meninggalkan halaman ujian. Tetap berada di halaman ujian selama pengerjaan.';
                    } else if (this.violationCount === 2) {
                        this.violationTitle = 'Peringatan 2 dari 3';
                        this.violationMessage = 'Anda kembali terdeteksi meninggalkan halaman ujian. Satu pelanggaran lagi akan mengakhiri sesi ujian Anda.';
                    }
                    this.showViolationModal = true;
                },

                closeViolationModal() {
                    this.showViolationModal = false;
                },

                handleLock() {
                    this.violationTitle = 'Sesi Ujian Dihentikan';
                    this.violationMessage = 'Anda telah mencapai batas pelanggaran. Silakan hubungi guru/pengawas untuk melakukan reset.';
                    this.showViolationModal = true;
                },

                autoSubmit() {
                    alert('Waktu ujian telah habis. Sesi Anda akan otomatis dikirimkan.');
                    if (window.cbtGetAnswers) {
                        const payloadInput = document.getElementById('answers_payload');
                        if (payloadInput) {
                            payloadInput.value = JSON.stringify(window.cbtGetAnswers());
                        }
                    }
                    const submitForm = document.getElementById('exam-submit-form');
                    if (submitForm) {
                        submitForm.submit();
                    }
                }
            }
        }
    </script>
</body>
</html>
