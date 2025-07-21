<x-layouts.app title="{{ $simulado->titulo }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        {{ $simulado->titulo }}
                    </h1>
                    <div class="flex items-center mt-2">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs px-2 py-0.5 rounded-full mr-2">
                            {{ $simulado->categoria_formatada }}
                        </span>
                        <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs px-2 py-0.5 rounded-full">
                            {{ $simulado->nivel_formatado }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('simulados.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Detalhes do Simulado -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-8">
                <div class="prose dark:prose-invert max-w-none">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">Sobre este Simulado</h2>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        {{ $simulado->descricao }}
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">Informações</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Duração: <strong>{{ $simulado->duracao }} minutos</strong></span>
                            </li>
                            <li class="flex items-center text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Questões: <strong>{{ $simulado->questoes_count }}</strong></span>
                            </li>
                            <li class="flex items-center text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Nota mínima para aprovação: <strong>{{ $simulado->passing_score }}%</strong></span>
                            </li>
                            <li class="flex items-center text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Pontos de recompensa: <strong>{{ $simulado->pontos_recompensa }}</strong></span>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">Seu Progresso</h3>
                        
                        @if($bestAttempt)
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $hasPassed ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' }} mr-4">
                                    <span class="text-2xl font-bold">{{ $bestAttempt->score }}%</span>
                                </div>
                                <div>
                                    <p class="font-medium {{ $hasPassed ? 'text-green-700 dark:text-green-400' : 'text-yellow-700 dark:text-yellow-400' }}">
                                        {{ $hasPassed ? 'Aprovado' : 'Não aprovado' }}
                                    </p>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                        Melhor tentativa: {{ $bestAttempt->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2 mt-4">
                                <a href="{{ route('simulados.result', ['id' => $simulado->id, 'tentativa' => $bestAttempt->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Ver Resultados
                                </a>
                                
                                @if($canTake)
                                    <button type="button" 
                                            onclick="startSimulado()"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Tentar Novamente
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-4">
                                <p class="text-blue-700 dark:text-blue-400">
                                    Você ainda não realizou este simulado.
                                </p>
                            </div>
                            
                            @if($canTake)
                                <button type="button" 
                                        onclick="startSimulado()"
                                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Iniciar Simulado
                                </button>
                            @else
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                                    <p class="text-yellow-700 dark:text-yellow-400">
                                        Você já tem uma tentativa em andamento para este simulado.
                                    </p>
                                    <a href="{{ route('simulados.history') }}" 
                                       class="inline-flex items-center mt-2 text-sm text-yellow-700 dark:text-yellow-400 hover:underline">
                                        Ver minhas tentativas
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Instruções -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">Instruções</h2>
                
                <div class="space-y-4 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="ml-3">
                            O simulado tem duração de <strong>{{ $simulado->duracao }} minutos</strong>. Um cronômetro será exibido para ajudar você a controlar o tempo.
                        </p>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="ml-3">
                            São <strong>{{ $simulado->questoes_count }} questões</strong> no total. Você precisa acertar pelo menos <strong>{{ $simulado->passing_score }}%</strong> para ser aprovado.
                        </p>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="ml-3">
                            Você pode navegar entre as questões livremente durante o simulado. Suas respostas são salvas automaticamente.
                        </p>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="ml-3">
                            Ao ser aprovado, você ganhará <strong>{{ $simulado->pontos_recompensa }} pontos</strong> e um certificado digital.
                        </p>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <p class="ml-3">
                            <strong>Atenção:</strong> Não atualize a página ou feche o navegador durante o simulado, pois isso pode resultar na perda de suas respostas.
                        </p>
                    </div>
                </div>
                
                @if($canTake)
                    <div class="mt-6 text-center">
                        <button type="button" 
                                onclick="startSimulado()"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $bestAttempt ? 'Tentar Novamente' : 'Iniciar Simulado' }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-bold text-hcp-secondary-900 dark:text-white mb-4">
                    Iniciar Simulado
                </h3>
                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                    Você está prestes a iniciar o simulado <strong>{{ $simulado->titulo }}</strong>. O cronômetro começará imediatamente.
                    <br><br>
                    Você tem <strong>{{ $simulado->duracao }} minutos</strong> para completar o simulado. Está pronto?
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeModal()"
                            class="px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-md text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700">
                        Cancelar
                    </button>
                    <button type="button" 
                            onclick="confirmStart()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
                        Iniciar Agora
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function startSimulado() {
            document.getElementById('confirmationModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }
        
        function confirmStart() {
            // Mostrar loading
            const modal = document.getElementById('confirmationModal');
            modal.innerHTML = `
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6 text-center">
                        <svg class="animate-spin h-10 w-10 text-hcp-primary-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Iniciando simulado, por favor aguarde...
                        </p>
                    </div>
                </div>
            `;
            
            // Enviar requisição para iniciar simulado
            fetch('{{ route('simulados.start', $simulado->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Erro: ' + data.message);
                    closeModal();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao iniciar o simulado. Por favor, tente novamente.');
                closeModal();
            });
        }
    </script>
</x-layouts.app>