<x-layouts.app title="Quiz: {{ $quiz->title }} - HCP Onboarding">
    <!-- Incluir CSS para feedback imediato -->
    <link rel="stylesheet" href="{{ asset('css/quiz-feedback.css') }}">
    
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header com Timer -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ $quiz->title }}
                        </h1>
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Tentativa {{ $attempt->attempt_number }} de {{ $quiz->max_attempts }}
                        </p>
                    </div>
                    
                    @if($quiz->time_limit)
                        <div class="text-center">
                            <div id="timer" class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                --:--
                            </div>
                            <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Tempo restante
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                        <span>Progresso</span>
                        <span class="quiz-progress-text">0 de <span class="total-questions">{{ $questions->count() }}</span></span>
                    </div>
                    <div class="quiz-progress-container">
                        <div class="quiz-progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Quiz Container -->
            <div class="quiz-container" data-quiz-id="{{ $quiz->id }}" data-attempt-id="{{ $attempt->id }}">
                <div class="quiz-question-container">
                    @if($questions->count() > 0)
                        @php $currentQuestion = $questions->first(); @endphp
                        <div class="quiz-question bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6" data-question-id="{{ $currentQuestion->id }}">
                            <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">{{ $currentQuestion->question }}</h3>
                            
                            <div class="quiz-options">
                                @if($currentQuestion->question_type === 'multiple_choice')
                                    @foreach($currentQuestion->getOptions() as $index => $option)
                                        @php $letter = chr(65 + $index); @endphp
                                        <div class="quiz-answer-option relative p-4 border rounded-lg mb-3 cursor-pointer hover:bg-gray-50 transition-colors" data-value="{{ $letter }}">
                                            <div class="flex items-center">
                                                <span class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm mr-3">{{ $letter }}</span>
                                                <span>{{ $option }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif($currentQuestion->question_type === 'true_false')
                                    <div class="quiz-answer-option relative p-4 border rounded-lg mb-3 cursor-pointer hover:bg-gray-50 transition-colors" data-value="true">
                                        <div class="flex items-center">
                                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm mr-3">V</span>
                                            <span>Verdadeiro</span>
                                        </div>
                                    </div>
                                    <div class="quiz-answer-option relative p-4 border rounded-lg mb-3 cursor-pointer hover:bg-gray-50 transition-colors" data-value="false">
                                        <div class="flex items-center">
                                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm mr-3">F</span>
                                            <span>Falso</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 text-center">
                            <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Não há questões disponíveis para este quiz.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let timeLimit = {{ $quiz->time_limit ? $attempt->getRemainingTime() : 0 }};
        let timerInterval;

        // Inicializar timer
        if (timeLimit > 0) {
            startTimer();
        }

        function startTimer() {
            updateTimerDisplay();
            
            timerInterval = setInterval(function() {
                timeLimit--;
                updateTimerDisplay();
                
                if (timeLimit <= 0) {
                    clearInterval(timerInterval);
                    alert('⏰ Tempo esgotado! O quiz será enviado automaticamente.');
                    window.location.href = "{{ route('quizzes.submit', [$quiz->id, $attempt->id]) }}";
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLimit / 60);
            const seconds = timeLimit % 60;
            const display = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            
            const timerElement = document.getElementById('timer');
            if (timerElement) {
                timerElement.textContent = display;
                
                // Mudar cor quando restam menos de 5 minutos
                if (timeLimit <= 300) {
                    timerElement.className = 'text-2xl font-bold text-red-600 dark:text-red-400';
                }
            }
        }

        // Confirmar antes de sair da página
        window.addEventListener('beforeunload', function(e) {
            if (timeLimit > 0) {
                e.preventDefault();
                e.returnValue = 'Você tem certeza que deseja sair? Seu progresso será perdido.';
            }
        });
    </script>
    
    <!-- Incluir script de feedback imediato -->
    <script src="{{ asset('js/quiz-feedback-manager.js') }}"></script>
</x-layouts.app>