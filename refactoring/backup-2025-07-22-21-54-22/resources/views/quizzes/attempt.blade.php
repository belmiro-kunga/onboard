<x-layouts.app title="Quiz: {{ $quiz->title }} - HCP Onboarding">
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
                        <span id="progress-text">0 de {{ $questions->count() }}</span>
                    </div>
                    <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                        <div id="progress-bar" class="bg-hcp-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Quiz Form -->
                            <form id="quiz-form" method="POST" action="{{ route('quizzes.submit', [$quiz->id, $attempt]) }}">
                @csrf
                
                <div class="space-y-6">
                    @foreach($questions as $index => $question)
                        <div class="question-card bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6" 
                             data-question="{{ $index + 1 }}">
                            
                            <!-- Question Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-hcp-500 text-white text-sm font-medium px-3 py-1 rounded-full mr-3">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wide">
                                            {{ $question->formatted_type }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">
                                        {!! nl2br(e($question->question)) !!}
                                    </h3>
                                </div>
                                
                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    {{ $question->points }} {{ $question->points === 1 ? 'ponto' : 'pontos' }}
                                </div>
                            </div>

                            <!-- Question Options -->
                            <div class="space-y-3">
                                @if($question->question_type === 'multiple_choice')
                                    @foreach($question->getOptions() as $optionKey => $option)
                                        <label class="flex items-center p-3 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $optionKey }}"
                                                   class="w-4 h-4 text-hcp-500 border-hcp-secondary-300 focus:ring-hcp-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700"
                                                   onchange="updateProgress()">
                                            <span class="ml-3 text-hcp-secondary-900 dark:text-white">
                                                {{ $option }}
                                            </span>
                                        </label>
                                    @endforeach
                                    
                                @elseif($question->question_type === 'true_false')
                                    <label class="flex items-center p-3 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="true"
                                               class="w-4 h-4 text-hcp-500 border-hcp-secondary-300 focus:ring-hcp-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700"
                                               onchange="updateProgress()">
                                        <span class="ml-3 text-hcp-secondary-900 dark:text-white">
                                            ✅ Verdadeiro
                                        </span>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="false"
                                               class="w-4 h-4 text-hcp-500 border-hcp-secondary-300 focus:ring-hcp-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700"
                                               onchange="updateProgress()">
                                        <span class="ml-3 text-hcp-secondary-900 dark:text-white">
                                            ❌ Falso
                                        </span>
                                    </label>
                                    
                                @elseif($question->question_type === 'fill_blank')
                                    <input type="text" 
                                           name="answers[{{ $question->id }}]" 
                                           class="w-full px-4 py-3 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white"
                                           placeholder="Digite sua resposta..."
                                           onchange="updateProgress()">
                                           
                                @elseif($question->question_type === 'drag_drop')
                                    <div class="drag-drop-container" data-question-id="{{ $question->id }}">
                                        <div class="mb-4">
                                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-3">
                                                🎯 Arraste os itens para a ordem correta:
                                            </p>
                                            <div class="drag-drop-source grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg border-2 border-dashed border-hcp-secondary-300 dark:border-hcp-secondary-600">
                                                @foreach($question->getOptions() as $optionKey => $option)
                                                    <div class="drag-item bg-white dark:bg-hcp-secondary-800 p-3 rounded-lg shadow-sm cursor-move border border-hcp-secondary-200 dark:border-hcp-secondary-600 hover:shadow-md transition-all duration-200 hover:scale-105" 
                                                         data-value="{{ $optionKey }}"
                                                         draggable="true">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                            </svg>
                                                            <span class="text-sm text-hcp-secondary-900 dark:text-white">{{ $option }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="drag-drop-target min-h-[120px] p-4 border-2 border-dashed border-hcp-primary-300 dark:border-hcp-primary-600 rounded-lg bg-hcp-primary-50 dark:bg-hcp-primary-900/20 transition-all duration-200">
                                            <p class="text-sm text-hcp-primary-600 dark:text-hcp-primary-400 text-center">
                                                📋 Solte os itens aqui na ordem correta
                                            </p>
                                        </div>
                                        <input type="hidden" name="answers[{{ $question->id }}]" class="drag-drop-answer" onchange="updateProgress()">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="mt-8 text-center">
                    <button type="submit" 
                            id="submit-btn"
                            class="px-8 py-3 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        🚀 Finalizar Quiz
                    </button>
                    
                    <p class="mt-2 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Certifique-se de revisar suas respostas antes de finalizar
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let totalQuestions = {{ $questions->count() }};
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
                    document.getElementById('quiz-form').submit();
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

        function updateProgress() {
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked, input[type="text"]:not([value=""])').length;
            const percentage = (answeredQuestions / totalQuestions) * 100;
            
            document.getElementById('progress-bar').style.width = percentage + '%';
            document.getElementById('progress-text').textContent = answeredQuestions + ' de ' + totalQuestions;
            
            // Habilitar botão de envio se todas as questões foram respondidas
            const submitBtn = document.getElementById('submit-btn');
            if (answeredQuestions === totalQuestions) {
                submitBtn.disabled = false;
                submitBtn.textContent = '🚀 Finalizar Quiz';
            } else {
                submitBtn.disabled = false; // Permitir envio parcial
                submitBtn.textContent = '📝 Finalizar Quiz (' + answeredQuestions + '/' + totalQuestions + ')';
            }
        }

        // Confirmar antes de sair da página
        window.addEventListener('beforeunload', function(e) {
            if (timeLimit > 0) {
                e.preventDefault();
                e.returnValue = 'Você tem certeza que deseja sair? Seu progresso será perdido.';
            }
        });

        // Confirmar envio
        document.getElementById('quiz-form').addEventListener('submit', function(e) {
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked, input[type="text"]:not([value=""])').length;
            
            if (answeredQuestions < totalQuestions) {
                if (!confirm('Você não respondeu todas as questões. Deseja continuar mesmo assim?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Parar timer
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            
            // Desabilitar botão para evitar duplo envio
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-btn').textContent = '⏳ Enviando...';
        });

        // Inicializar progresso
        updateProgress();

        // Drag and Drop functionality
        let draggedElement = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
        });

        function initializeDragAndDrop() {
            const dragItems = document.querySelectorAll('.drag-item');
            const dropTargets = document.querySelectorAll('.drag-drop-target');

            dragItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            });

            dropTargets.forEach(target => {
                target.addEventListener('dragover', handleDragOver);
                target.addEventListener('drop', handleDrop);
                target.addEventListener('dragenter', handleDragEnter);
                target.addEventListener('dragleave', handleDragLeave);
            });
        }

        function handleDragStart(e) {
            draggedElement = this;
            this.style.opacity = '0.5';
            this.classList.add('dragging');
            
            // Haptic feedback on drag start
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        }

        function handleDragEnd(e) {
            this.style.opacity = '1';
            this.classList.remove('dragging');
            draggedElement = null;
        }

        function handleDragOver(e) {
            e.preventDefault();
            return false;
        }

        function handleDragEnter(e) {
            e.preventDefault();
            this.classList.add('drag-over');
            this.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
        }

        function handleDragLeave(e) {
            this.classList.remove('drag-over');
            this.style.backgroundColor = '';
        }

        function handleDrop(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            this.style.backgroundColor = '';

            if (draggedElement) {
                // Clone the dragged element
                const clonedElement = draggedElement.cloneNode(true);
                clonedElement.classList.add('dropped-item');
                clonedElement.draggable = false;
                
                // Add remove button
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '×';
                removeBtn.className = 'ml-2 text-red-500 hover:text-red-700 font-bold';
                removeBtn.type = 'button';
                removeBtn.onclick = function() {
                    clonedElement.remove();
                    updateDragDropAnswer(this.closest('.drag-drop-container'));
                };
                clonedElement.querySelector('div').appendChild(removeBtn);
                
                // Add to target
                this.appendChild(clonedElement);
                
                // Update hidden input
                updateDragDropAnswer(this.closest('.drag-drop-container'));
                
                // Haptic feedback on successful drop
                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }
                
                // Visual feedback
                clonedElement.style.animation = 'pulse 0.5s ease-in-out';
            }
        }

        function updateDragDropAnswer(container) {
            const target = container.querySelector('.drag-drop-target');
            const droppedItems = target.querySelectorAll('.dropped-item');
            const answer = Array.from(droppedItems).map(item => item.dataset.value);
            
            const hiddenInput = container.querySelector('.drag-drop-answer');
            hiddenInput.value = JSON.stringify(answer);
            
            // Trigger progress update
            updateProgress();
        }

        // Enhanced progress update to include drag-drop questions
        function updateProgress() {
            let answeredQuestions = 0;
            
            // Count radio buttons
            answeredQuestions += document.querySelectorAll('input[type="radio"]:checked').length;
            
            // Count text inputs with values
            const textInputs = document.querySelectorAll('input[type="text"]');
            textInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    answeredQuestions++;
                }
            });
            
            // Count drag-drop questions with answers
            const dragDropAnswers = document.querySelectorAll('.drag-drop-answer');
            dragDropAnswers.forEach(input => {
                if (input.value && input.value !== '[]') {
                    answeredQuestions++;
                }
            });
            
            const percentage = (answeredQuestions / totalQuestions) * 100;
            
            document.getElementById('progress-bar').style.width = percentage + '%';
            document.getElementById('progress-text').textContent = answeredQuestions + ' de ' + totalQuestions;
            
            // Update submit button
            const submitBtn = document.getElementById('submit-btn');
            if (answeredQuestions === totalQuestions) {
                submitBtn.disabled = false;
                submitBtn.textContent = '🚀 Finalizar Quiz';
                submitBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'animate-pulse');
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = '📝 Finalizar Quiz (' + answeredQuestions + '/' + totalQuestions + ')';
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'animate-pulse');
                submitBtn.classList.add('bg-green-500', 'hover:bg-green-600');
            }
        }

        // Haptic feedback for answer selection
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[type="radio"]')) {
                // Haptic feedback for correct selection
                if (navigator.vibrate) {
                    navigator.vibrate(30);
                }
                
                // Visual feedback
                const label = e.target.closest('label');
                if (label) {
                    label.style.animation = 'bounce 0.3s ease-in-out';
                    setTimeout(() => {
                        label.style.animation = '';
                    }, 300);
                }
            }
        });

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 20%, 60%, 100% { transform: translateY(0); }
                40% { transform: translateY(-10px); }
                80% { transform: translateY(-5px); }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .dragging {
                transform: rotate(5deg);
                z-index: 1000;
            }
            
            .drag-over {
                border-color: #3B82F6 !important;
                background-color: rgba(59, 130, 246, 0.1) !important;
            }
            
            .dropped-item {
                margin-bottom: 8px;
                border-color: #10B981 !important;
                background-color: rgba(16, 185, 129, 0.1) !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-layouts.app>