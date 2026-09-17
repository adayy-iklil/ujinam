@extends('layouts.exam')

@section('content')
<div x-data="examQuestionsData()" class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- Left Column: Question & Options (3 cols) -->
    <div class="lg:col-span-3 space-y-6">
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm min-h-[420px] flex flex-col justify-between">
            <div>
                <!-- Question Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Soal Nomor</span>
                        <span class="bg-slate-900 text-white font-mono font-bold text-sm px-2.5 py-0.5 rounded" x-text="currentIndex + 1"></span>
                        <span class="text-xs text-slate-400">dari <span x-text="questions.length"></span></span>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Bobot: <strong class="text-slate-700" x-text="currentQuestion.points">1</strong> Poin</span>
                </div>

                <!-- Question Text -->
                <div class="text-sm md:text-base text-slate-800 leading-relaxed font-medium mb-6" x-html="currentQuestion.text"></div>

                <!-- Options Radio List (A-E) -->
                <div class="space-y-3">
                    <template x-for="option in currentQuestion.options" :key="option.id">
                        <label 
                            class="flex items-start p-3.5 rounded-md border text-xs sm:text-sm cursor-pointer transition select-none"
                            :class="answers[currentQuestion.id] == option.id ? 'bg-blue-50 border-blue-600 text-blue-950 font-semibold shadow-sm' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-800'"
                        >
                            <input 
                                type="radio" 
                                :name="'q_' + currentQuestion.id" 
                                :value="option.id" 
                                :checked="answers[currentQuestion.id] == option.id"
                                @change="selectAnswer(currentQuestion.id, option.id)"
                                class="mt-0.5 text-blue-600 focus:ring-blue-500 h-4 w-4 border-slate-300"
                            >
                            <span class="ml-3 font-mono font-bold text-slate-600 uppercase" x-text="option.key + '.'"></span>
                            <span class="ml-2 flex-1 leading-normal" x-text="option.text"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Bottom Navigation Bar -->
            <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between">
                <button 
                    type="button" 
                    @click="prevQuestion()" 
                    :disabled="currentIndex === 0"
                    class="bg-slate-100 hover:bg-slate-200 disabled:opacity-40 disabled:cursor-not-allowed text-slate-800 font-semibold text-xs py-2 px-4 rounded border border-slate-300 transition"
                >
                    ← Sebelumnya
                </button>

                <button 
                    type="button" 
                    @click="openFinishModal()" 
                    class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs py-2.5 px-5 rounded transition shadow-sm"
                >
                    Selesaikan Ujian
                </button>

                <button 
                    type="button" 
                    @click="nextQuestion()" 
                    :disabled="currentIndex === questions.length - 1"
                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold text-xs py-2 px-4 rounded transition"
                >
                    Selanjutnya →
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column: Question Navigator Grid (1 col) -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm sticky top-20">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 pb-2 border-b border-slate-100">
                Navigasi Soal
            </h3>

            <!-- Grid Numbers -->
            <div class="grid grid-cols-5 gap-2 max-h-[350px] overflow-y-auto p-0.5">
                <template x-for="(q, idx) in questions" :key="q.id">
                    <button 
                        type="button" 
                        @click="currentIndex = idx"
                        class="h-9 rounded font-mono text-xs font-bold transition flex items-center justify-center border"
                        :class="{
                            'ring-2 ring-blue-600 ring-offset-1 font-bold': currentIndex === idx,
                            'bg-blue-600 text-white border-blue-700 shadow-sm': answers[q.id],
                            'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200': !answers[q.id] && currentIndex !== idx
                        }"
                    >
                        <span x-text="idx + 1 < 10 ? '0' + (idx + 1) : (idx + 1)"></span>
                    </button>
                </template>
            </div>

            <!-- Legend Summary -->
            <div class="mt-5 pt-3 border-t border-slate-100 text-[11px] space-y-1.5 text-slate-600">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded bg-blue-600 inline-block"></span>
                    <span>Sudah dijawab (<strong x-text="answeredCount">0</strong>)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded bg-slate-100 border border-slate-300 inline-block"></span>
                    <span>Belum dijawab (<strong x-text="unansweredCount">0</strong>)</span>
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
    <div x-show="showFinishModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 max-w-md w-full p-6 text-center">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-lg">
                ?
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Selesai Ujian</h3>
            
            <div class="text-xs text-slate-600 my-4 bg-slate-50 p-3 rounded border border-slate-200 leading-relaxed">
                <template x-if="unansweredCount > 0">
                    <p class="text-amber-800 font-semibold mb-1">
                        ⚠️ Anda masih memiliki <span x-text="unansweredCount"></span> soal yang belum dijawab.
                    </p>
                </template>
                <p>Apakah Anda sudah yakin ingin mengakhiri sesi ujian ini? Jawaban tidak dapat diubah kembali setelah dikirimkan.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" @click="showFinishModal = false" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs py-2.5 px-4 rounded border border-slate-300">
                    Kembali
                </button>

                <button type="button" @click="confirmSubmit()" class="w-1/2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2.5 px-4 rounded shadow-sm">
                    Ya, Selesaikan Ujian
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
