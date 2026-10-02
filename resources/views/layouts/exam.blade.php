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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; user-select: none; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-100/80 text-slate-900 antialiased min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white" x-data="examEngine()" @save-answer.window="saveAnswer($event.detail.questionId, $event.detail.optionId)">

    <!-- Modern CBT Exam Top Bar -->
    <header class="bg-slate-950/95 backdrop-blur-md text-white border-b border-slate-800 sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo & Exam Title -->
                <div class="flex items-center space-x-3.5 truncate">
                    <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-9 w-auto object-contain flex-shrink-0" style="height: 36px; width: auto; max-height: 36px;">
                    <div class="truncate">
                        <div class="flex items-center gap-2">
                            <h1 class="font-bold text-sm sm:text-base text-white tracking-tight truncate">{{ $attempt->exam->title }}</h1>
                            <span class="text-[10px] uppercase font-bold tracking-wider bg-slate-800 text-slate-300 border border-slate-700 px-2 py-0.5 rounded hidden md:inline">
                                {{ $attempt->exam->subject->code ?? 'CBT' }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-400 block -mt-0.5 truncate hidden sm:block">
                            Peserta: <strong class="text-slate-200">{{ $attempt->student->name }}</strong> (NIS: {{ $attempt->student->nis }})
                        </span>
                    </div>
                </div>

                <!-- Right: Autosave & Modern Countdown Timer -->
                <div class="flex items-center space-x-3 sm:space-x-4 flex-shrink-0">
                    <!-- Autosave Indicator -->
                    <div class="text-xs flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 shadow-inner">
                        <span class="w-2 h-2 rounded-full" :class="isSaving ? 'bg-amber-400 animate-ping' : 'bg-emerald-400'"></span>
                        <span class="text-slate-300 font-medium text-[11px] hidden sm:inline" x-text="saveStatus">Tersimpan</span>
                    </div>

                    <!-- Countdown Timer Card -->
                    <div class="bg-slate-900 border border-slate-700 px-3.5 py-1.5 rounded-lg text-center shadow-xs min-w-[115px]">
                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider leading-none">Sisa Waktu</div>
                        <div class="text-base font-mono font-bold tracking-wider mt-0.5" :class="remainingSeconds < 300 ? 'text-rose-400 animate-pulse' : 'text-emerald-400'" x-text="formattedTimer">
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

    <!-- Modern Violation Warning Modal -->
    <div x-show="showViolationModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-md w-full p-6 text-center transform transition-all">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mx-auto mb-3 border border-rose-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-1" x-text="violationTitle">Peringatan Pelanggaran</h3>
            <p class="text-xs text-slate-600 mb-5 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100" x-text="violationMessage"></p>

            <template x-if="violationCount < 3">
                <button type="button" @click="closeViolationModal()" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs py-2.5 px-4 rounded-lg transition-colors shadow-xs">
                    Saya Mengerti, Lanjutkan Ujian
                </button>
            </template>

            <template x-if="violationCount >= 3">
                <a href="{{ route('siswa.dashboard') }}" class="w-full block bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs py-2.5 px-4 rounded-lg transition-colors shadow-xs">
                    Kembali ke Dashboard Siswa
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
                        this.violationTitle = 'Peringatan Pelanggaran (1/3)';
                        this.violationMessage = 'Anda terdeteksi berpindah jendela aplikasi atau tab browser. Harap tetap berada di halaman ujian.';
                    } else if (this.violationCount === 2) {
                        this.violationTitle = 'Peringatan Terakhir (2/3)';
                        this.violationMessage = 'Anda kembali terdeteksi meninggalkan halaman ujian. Satu pelanggaran lagi akan otomatis mengunci sesi ujian Anda.';
                    }
                    this.showViolationModal = true;
                },

                closeViolationModal() {
                    this.showViolationModal = false;
                },

                handleLock() {
                    this.violationTitle = 'Sesi Ujian Terkunci';
                    this.violationMessage = 'Anda telah mencapai batas 3 kali pelanggaran. Sesi ujian dihentikan demi integritas ujian. Hubungi pengawas ruang untuk verifikasi dan pembukaan kunci.';
                    this.showViolationModal = true;
                },

                autoSubmit() {
                    alert('Waktu ujian telah habis. Sesi ujian Anda akan otomatis dikirimkan ke server.');
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
