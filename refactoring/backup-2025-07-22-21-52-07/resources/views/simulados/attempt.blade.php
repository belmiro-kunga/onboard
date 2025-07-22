<x-layouts.app title="Simulado: {{ $simulado->titulo }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header com Timer -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ $simulado->titulo }}
                        </h1>
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Simulado em andamento
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div id="timer" class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                            --:--
                        </div>
                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                            Tempo restante
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                        <span>Progresso</span>
                        <span id="progress-text">0 de {{ $questoes->count() }}</span>
                    </div>
                    <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                        <div id="progress-bar" class="bg-hcp-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Navegação de Questões -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-4 mb-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($questoes as $index => $questao)
                        @php
                            $isAnswered = isset($respostas[$questao->id]);
                            $buttonClass = $isAnswered 
                                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 border-green-300 dark:border-green-700' 
                                : 'bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 border-hcp-secondary-300 dark:border-hcp-secondary-600';
                        @endphp
                        <button type="button" 
                                onclick="showQuestion({{ $index }})"
                                class="question-nav-btn w-10 h-10 flex items-center justify-center rounded-md border {{ $buttonClass }} hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors"
                                data-index="{{ $index }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Questões -->
            <div id="questions-container">
                @foreach($questoes as $index => $questao)
                    <div class="question-card bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-6 {{ $index > 0 ? 'hidden' : '' }}" data-index="{{ $index }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <span class="bg-hcp-500 text-white text-sm font-medium px-3 py-1 rounded-full mr-3">
                                    {{ $index + 1 }} / {{ $questoes->count() }}
                                </span>
                            </div>
                            
                            <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                @if(isset($respostas[$questao->id]))
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Respondida
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-yellow-600 dark:text-yellow-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pendente
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-6">
                            {{ $questao->pergunta }}
                        </h3>
                        
                        <div class="space-y-3 mb-6">
                            @php
                                $opcoes = is_array($questao->opcoes) 
                                    ? $questao->opcoes 
                                    : (json_decode($questao->opcoes, true) ?? []);
                            @endphp
                            
                            @foreach($opcoes as $key => $opcao)
                                <div class="option-item flex items-center p-3 border rounded-lg cursor-pointer hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors {{ isset($respostas[$questao->id]) && $respostas[$questao->id]->resposta === $key ? 'border-hcp-primary-500 bg-hcp-primary-50 dark:bg-hcp-primary-900/20' : 'border-hcp-secondary-200 dark:border-hcp-secondary-700' }}"
                                     onclick="selectOption('{{ $questao->id }}', '{{ $key }}', this)">
                                    <div class="flex-shrink-0 w-5 h-5 border rounded-full mr-3 flex items-center justify-center {{ isset($respostas[$questao->id]) && $respostas[$questao->id]->resposta === $key ? 'border-hcp-primary-500 bg-hcp-primary-500' : 'border-hcp-secondary-400' }}">
                                        @if(isset($respostas[$questao->id]) && $respostas[$questao->id]->resposta === $key)
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-hcp-secondary-900 dark:text-white">{{ $opcao }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="flex justify-between">
                            @if($index > 0)
                                <button type="button" 
                                        onclick="showQuestion({{ $index - 1 }})"
                                        class="inline-flex items-center px-4 py-2 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Anterior
                                </button>
                            @else
                                <div></div>
                            @endif
                            
                            @if($index < $questoes->count() - 1)
                                <button type="button" 
                                        onclick="showQuestion({{ $index + 1 }})"
                                        class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    Próxima
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            @else
                                <button type="button" 
                                        onclick="showFinishConfirmation()"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Finalizar Simulado
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botão Finalizar (fixo no rodapé) -->
            <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-hcp-secondary-800 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700 p-4 flex justify-between items-center">
                <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    <span id="answered-count">0</span> de {{ $questoes->count() }} questões respondidas
                </div>
                
                <button type="button" 
                        onclick="showFinishConfirmation()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Finalizar Simulado
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação para Finalizar -->
    <div id="finishModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-bold text-hcp-secondary-900 dark:text-white mb-4">
                    Finalizar Simulado
                </h3>
                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                    Você respondeu <span id="modal-answered-count">0</span> de {{ $questoes->count() }} questões.
                    <br><br>
                    Tem certeza que deseja finalizar o simulado? Esta ação não pode ser desfeita.
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeFinishModal()"
                            class="px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-md text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700">
                        Continuar Respondendo
                    </button>
                    <button type="button" 
                            onclick="finishSimulado()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
                        Finalizar Agora
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Variáveis globais
        let currentQuestionIndex = 0;
        let answeredQuestions = {};
        let startTime = new Date();
        let timeLimit = {{ $simulado->duracao * 60 }}; // em segundos
        let timerInterval;
        let csrfToken = '{{ csrf_token() }}';
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar respostas já salvas
            @foreach($respostas as $resposta)
                answeredQuestions[{{ $resposta->questao_id }}] = true;
            @endforeach
            
            updateProgress();
            startTimer();
            
            // Prevenir saída acidental
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = 'Você tem certeza que deseja sair? Seu progresso será salvo, mas o simulado continuará em andamento.';
            });
        });
        
        // Mostrar questão específica
        function showQuestion(index) {
            // Esconder todas as questões
            document.querySelectorAll('.question-card').forEach(card => {
                card.classList.add('hidden');
            });
            
            // Mostrar a questão selecionada
            document.querySelector(`.question-card[data-index="${index}"]`).classList.remove('hidden');
            
            // Atualizar índice atual
            currentQuestionIndex = index;
            
            // Atualizar botões de navegação
            document.querySelectorAll('.question-nav-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-hcp-primary-500');
            });
            
            document.querySelector(`.question-nav-btn[data-index="${index}"]`).classList.add('ring-2', 'ring-hcp-primary-500');
            
            // Scroll para o topo
            window.scrollTo(0, 0);
        }
        
        // Selecionar opção
        function selectOption(questionId, option, element) {
            // Remover seleção anterior
            element.parentNode.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('border-hcp-primary-500', 'bg-hcp-primary-50', 'dark:bg-hcp-primary-900/20');
                item.classList.add('border-hcp-secondary-200', 'dark:border-hcp-secondary-700');
                
                // Remover o círculo preenchido
                const circle = item.querySelector('.flex-shrink-0');
                circle.classList.remove('border-hcp-primary-500', 'bg-hcp-primary-500');
                circle.classList.add('border-hcp-secondary-400');
                circle.innerHTML = '';
            });
            
            // Adicionar seleção atual
            element.classList.remove('border-hcp-secondary-200', 'dark:border-hcp-secondary-700');
            element.classList.add('border-hcp-primary-500', 'bg-hcp-primary-50', 'dark:bg-hcp-primary-900/20');
            
            // Adicionar círculo preenchido
            const circle = element.querySelector('.flex-shrink-0');
            circle.classList.remove('border-hcp-secondary-400');
            circle.classList.add('border-hcp-primary-500', 'bg-hcp-primary-500');
            circle.innerHTML = `
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            `;
            
            // Calcular tempo gasto na questão
            const timeSpent = Math.floor((new Date() - startTime) / 1000);
            
            // Salvar resposta
            saveAnswer(questionId, option, timeSpent);
        }
        
        // Salvar resposta no servidor
        function saveAnswer(questionId, option, timeSpent) {
            // Mostrar indicador de salvamento
            const questionCard = document.querySelector(`.question-card[data-index="${currentQuestionIndex}"]`);
            const statusIndicator = questionCard.querySelector('.text-sm');
            statusIndicator.innerHTML = `
                <span class="inline-flex items-center text-blue-600 dark:text-blue-400">
                    <svg class="animate-spin w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Salvando...
                </span>
            `;
            
            // Enviar para o servidor
            fetch('{{ route('simulados.answer', ['id' => $simulado->id, 'tentativa' => $tentativa->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    questao_id: questionId,
                    resposta: option,
                    tempo_questao: timeSpent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar status
                    statusIndicator.innerHTML = `
                        <span class="inline-flex items-center text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Respondida
                        </span>
                    `;
                    
                    // Atualizar botão de navegação
                    document.querySelector(`.question-nav-btn[data-index="${currentQuestionIndex}"]`).classList.remove('bg-white', 'dark:bg-hcp-secondary-700', 'text-hcp-secondary-700', 'dark:text-hcp-secondary-300', 'border-hcp-secondary-300', 'dark:border-hcp-secondary-600');
                    document.querySelector(`.question-nav-btn[data-index="${currentQuestionIndex}"]`).classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-800', 'dark:text-green-400', 'border-green-300', 'dark:border-green-700');
                    
                    // Marcar como respondida
                    answeredQuestions[questionId] = true;
                    
                    // Atualizar progresso
                    updateProgress();
                    
                    // Feedback visual
                    if (data.correta) {
                        // Haptic feedback para resposta correta
                        if (navigator.vibrate) {
                            navigator.vibrate(100);
                        }
                    }
                    
                    // Resetar tempo para próxima questão
                    startTime = new Date();
                } else {
                    // Mostrar erro
                    statusIndicator.innerHTML = `
                        <span class="inline-flex items-center text-red-600 dark:text-red-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Erro ao salvar
                        </span>
                    `;
                    
                    // Mostrar mensagem de erro
                    alert('Erro ao salvar resposta: ' + (data.message || 'Tente novamente'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                
                // Mostrar erro
                statusIndicator.innerHTML = `
                    <span class="inline-flex items-center text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Erro ao salvar
                    </span>
                `;
                
                // Mostrar mensagem de erro
                alert('Erro ao salvar resposta. Verifique sua conexão e tente novamente.');
            });
        }
        
        // Atualizar progresso
        function updateProgress() {
            const answeredCount = Object.keys(answeredQuestions).length;
            const totalQuestions = {{ $questoes->count() }};
            const percentage = Math.round((answeredCount / totalQuestions) * 100);
            
            // Atualizar barra de progresso
            document.getElementById('progress-bar').style.width = percentage + '%';
            document.getElementById('progress-text').textContent = answeredCount + ' de ' + totalQuestions;
            document.getElementById('answered-count').textContent = answeredCount;
            
            // Atualizar contador no modal
            document.getElementById('modal-answered-count').textContent = answeredCount;
        }
        
        // Iniciar timer
        function startTimer() {
            // Calcular tempo restante
            const startedAt = new Date('{{ $tentativa->iniciado_em }}');
            const now = new Date();
            const elapsedSeconds = Math.floor((now - startedAt) / 1000);
            const remainingSeconds = Math.max(0, timeLimit - elapsedSeconds);
            
            updateTimerDisplay(remainingSeconds);
            
            timerInterval = setInterval(function() {
                const now = new Date();
                const elapsedSeconds = Math.floor((now - startedAt) / 1000);
                const remainingSeconds = Math.max(0, timeLimit - elapsedSeconds);
                
                updateTimerDisplay(remainingSeconds);
                
                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    alert('⏰ Tempo esgotado! O simulado será finalizado automaticamente.');
                    finishSimulado();
                }
            }, 1000);
        }
        
        // Atualizar display do timer
        function updateTimerDisplay(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            const display = minutes.toString().padStart(2, '0') + ':' + remainingSeconds.toString().padStart(2, '0');
            
            const timerElement = document.getElementById('timer');
            timerElement.textContent = display;
            
            // Mudar cor quando restam menos de 5 minutos
            if (seconds <= 300) {
                timerElement.className = 'text-2xl font-bold text-red-600 dark:text-red-400';
            }
        }
        
        // Mostrar confirmação de finalização
        function showFinishConfirmation() {
            document.getElementById('finishModal').classList.remove('hidden');
        }
        
        // Fechar modal de finalização
        function closeFinishModal() {
            document.getElementById('finishModal').classList.add('hidden');
        }
        
        // Finalizar simulado
        function finishSimulado() {
            // Mostrar loading
            const modal = document.getElementById('finishModal');
            modal.innerHTML = `
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6 text-center">
                        <svg class="animate-spin h-10 w-10 text-hcp-primary-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Finalizando simulado, por favor aguarde...
                        </p>
                    </div>
                </div>
            `;
            
            // Remover evento beforeunload
            window.removeEventListener('beforeunload', function(){});
            
            // Enviar requisição para finalizar simulado
            fetch('{{ route('simulados.finish', ['id' => $simulado->id, 'tentativa' => $tentativa->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Parar timer
                    clearInterval(timerInterval);
                    
                    // Redirecionar para página de resultados
                    window.location.href = data.redirect_url;
                } else {
                    alert('Erro: ' + data.message);
                    closeFinishModal();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao finalizar o simulado. Por favor, tente novamente.');
                closeFinishModal();
            });
        }
    </script>
</x-layouts.app>