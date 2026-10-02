@extends('layouts.exam')

@section('content')
<div x-data="examQuestionsData()" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Left Column: Question & Options (8 of 12 cols on desktop) -->
    <div class="lg:col-span-8 space-y-5">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-xs min-h-[460px] flex flex-col justify-between">
            <div>
                <!-- Question Top Indicator -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-5">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Soal Nomor</span>
                        <span class="bg-slate-900 text-white font-mono font-bold text-sm px-2.5 py-0.5 rounded-lg shadow-xs" x-text="currentIndex + 1 < 10 ? '0' + (currentIndex + 1) : (currentIndex + 1)"></span>
                        <span class="text-xs text-slate-400">/ <span class="font-semibold text-slate-600" x-text="questions.length"></span></span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700">
                            Bobot: <strong class="font-mono text-slate-900" x-text="currentQuestion.points">1</strong> Poin
                        </span>
                    </div>
                </div>

                <!-- Question Text -->
                <div class="text-base sm:text-lg text-slate-900 leading-relaxed font-semibold mb-6" x-html="currentQuestion.text"></div>

                <!-- Options List (A - E) -->
                <div class="space-y-3">
                    <template x-for="option in currentQuestion.options" :key="option.id">
                        <label 
                            class="group relative flex items-start p-3.5 sm:p-4 rounded-xl border-2 cursor-pointer transition-colors duration-150 select-none"
                            :class="answers[currentQuestion.id] == option.id 
                                ? 'bg-blue-50/70 border-blue-600 text-slate-900 shadow-xs' 
                                : 'bg-white hover:bg-slate-50 hover:border-slate-300 border-slate-200 text-slate-800'"
                        >
                            <input 
                                type="radio" 
                                :name="'q_' + currentQuestion.id" 
                                :value="option.id" 
                                :checked="answers[currentQuestion.id] == option.id"
                                @change="selectAnswer(currentQuestion.id, option.id)"
                                class="sr-only"
                            >
                            
                            <!-- Key Badge (A, B, C, D, E) -->
                            <span 
                                class="w-7 h-7 rounded-lg font-mono font-bold text-xs flex items-center justify-center flex-shrink-0 transition-colors uppercase"
                                :class="answers[currentQuestion.id] == option.id 
                                    ? 'bg-blue-600 text-white shadow-xs' 
                                    : 'bg-slate-100 border border-slate-300 text-slate-700 group-hover:bg-slate-200'"
                                x-text="option.key"
                            ></span>

                            <!-- Option Text -->
                            <span class="ml-3 flex-1 text-sm sm:text-base leading-relaxed pt-0.5 font-medium" x-text="option.text"></span>

                            <!-- Active Indicator Checkmark -->
                            <span 
                                class="w-5 h-5 rounded-full flex items-center justify-center transition-opacity flex-shrink-0 mt-0.5"
                                :class="answers[currentQuestion.id] == option.id ? 'opacity-100 text-blue-600' : 'opacity-0'"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Bottom Navigation Bar -->
            <div class="pt-5 mt-6 border-t border-slate-100 flex items-center justify-between gap-3">
                <button 
                    type="button" 
                    @click="prevQuestion()" 
                    :disabled="currentIndex === 0"
                    class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed text-slate-700 font-semibold text-xs py-2 px-3.5 rounded-lg border border-slate-300 shadow-xs transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span>Sebelumnya</span>
                </button>

                <button 
                    type="button" 
                    @click="openFinishModal()" 
                    class="bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs py-2 px-4 rounded-lg shadow-xs transition-colors"
                >
                    Selesaikan Ujian
                </button>

                <button 
                    type="button" 
                    @click="nextQuestion()" 
                    :disabled="currentIndex === questions.length - 1"
                    class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold text-xs py-2 px-4 rounded-lg shadow-xs transition-colors"
                >
                    <span>Selanjutnya</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column: Question Navigator Grid (4 of 12 cols on desktop) -->
    <div class="lg:col-span-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs sticky top-20">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                    Navigasi Soal
                </h2>
                <span class="text-xs font-mono font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    <span class="font-bold text-blue-600" x-text="answeredCount">0</span>/<span x-text="questions.length">0</span> Terjawab
                </span>
            </div>

            <!-- Number Buttons Grid -->
            <div class="grid grid-cols-5 gap-2 max-h-[360px] overflow-y-auto p-1">
                <template x-for="(q, idx) in questions" :key="q.id">
                    <button 
                        type="button" 
                        @click="currentIndex = idx"
                        class="h-9 rounded-lg font-mono text-xs font-semibold transition-all duration-150 flex items-center justify-center border"
                        :class="{
                            'ring-2 ring-slate-900 ring-offset-2 scale-105 border-slate-900': currentIndex === idx,
                            'bg-blue-600 text-white border-blue-600 font-bold': answers[q.id],
                            'bg-white text-slate-700 border-slate-300 hover:bg-slate-100': !answers[q.id] && currentIndex !== idx
                        }"
                    >
                        <span x-text="idx + 1 < 10 ? '0' + (idx + 1) : (idx + 1)"></span>
                    </button>
                </template>
            </div>

            <!-- Legend Summary -->
            <div class="mt-4 pt-3 border-t border-slate-100 text-xs space-y-1.5 text-slate-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded bg-blue-600 inline-block"></span>
                        <span class="font-medium">Sudah dijawab</span>
                    </div>
                    <span class="font-mono font-semibold text-slate-800" x-text="answeredCount">0</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded bg-white border border-slate-300 inline-block"></span>
                        <span class="font-medium">Belum dijawab</span>
                    </div>
                    <span class="font-mono font-semibold text-slate-500" x-text="unansweredCount">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Hidden Form -->
    <form id="exam-submit-form" action="{{ route('siswa.attempts.submit', $attempt->id) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="answers_payload" id="answers_payload" value="">
    </form>

    <!-- Confirmation Finish Modal -->
    <div x-show="showFinishModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-md w-full p-6 text-center transform transition-all">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mx-auto mb-3 border border-amber-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Selesai Ujian</h3>
            
            <div class="text-xs text-slate-600 my-4 bg-slate-50 p-3.5 rounded-xl border border-slate-200 leading-relaxed text-left">
                <template x-if="unansweredCount > 0">
                    <div class="p-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 font-semibold mb-2.5 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Anda masih memiliki <strong x-text="unansweredCount"></strong> butir soal yang belum dijawab.</span>
                    </div>
                </template>
                <p>Apakah Anda yakin ingin mengakhiri sesi pengerjaan ujian? Setelah dikumpulkan, lembar jawaban akan dinilai oleh server dan tidak dapat diubah kembali.</p>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-1">
                <button type="button" @click="showFinishModal = false" class="w-1/2 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs py-2.5 px-3 rounded-lg border border-slate-300 transition-colors">
                    Periksa Kembali
                </button>

                <button type="button" @click="confirmSubmit()" class="w-1/2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs py-2.5 px-3 rounded-lg shadow-xs transition-colors">
                    Ya, Kumpulkan Ujian
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function examQuestionsData() {
        return {
            currentIndex: 0,
            showFinishModal: false,
            questions: [
                @foreach($attemptQuestions as $aq)
                {
                    id: {{ $aq->question->id }},
                    points: {{ $aq->question->points }},
                    text: `{!! addslashes(nl2br(e($aq->question->question_text))) !!}`,
                    options: [
                        @foreach($aq->question->options as $opt)
                        {
                            id: {{ $opt->id }},
                            key: '{{ $opt->option_key }}',
                            text: `{!! addslashes(e($opt->option_text)) !!}`
                        },
                        @endforeach
                    ]
                },
                @endforeach
            ],
            answers: {
                @foreach($savedAnswers as $qId => $optId)
                {{ $qId }}: {{ $optId }},
                @endforeach
            },

            init() {
                window.cbtGetAnswers = () => this.answers;
            },

            get currentQuestion() {
                return this.questions[this.currentIndex] || {};
            },

            get answeredCount() {
                return Object.keys(this.answers).length;
            },

            get unansweredCount() {
                return this.questions.length - this.answeredCount;
            },

            selectAnswer(questionId, optionId) {
                this.answers[questionId] = optionId;

                // Priority 1: Call global layout function if available
                if (typeof window.cbtSaveAnswer === 'function') {
                    window.cbtSaveAnswer(questionId, optionId);
                } else {
                    // Priority 2: Dispatch event to window
                    window.dispatchEvent(new CustomEvent('save-answer', {
                        detail: { questionId: questionId, optionId: optionId }
                    }));
                }
            },

            nextQuestion() {
                if (this.currentIndex < this.questions.length - 1) {
                    this.currentIndex++;
                }
            },

            prevQuestion() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                }
            },

            openFinishModal() {
                this.showFinishModal = true;
            },

            confirmSubmit() {
                const payloadInput = document.getElementById('answers_payload');
                if (payloadInput) {
                    payloadInput.value = JSON.stringify(this.answers);
                }
                document.getElementById('exam-submit-form').submit();
            }
        }
    }
</script>
@endsection
