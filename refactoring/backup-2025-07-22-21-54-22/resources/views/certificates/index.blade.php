<x-layouts.employee title="Certificados">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        Meus Certificados 🏆
                    </h1>
                    <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Visualize e gerencie seus certificados digitais conquistados
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <button type="button" 
                            onclick="document.getElementById('requestCertificateModal').classList.remove('hidden')"
                            class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Solicitar Certificado
                    </button>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Certificados</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['total_certificates'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Certificados Válidos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['valid_certificates'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Este Mês</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['this_month'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Categorias</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ count($stats['categories'] ?? []) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Certificados -->
            @if(isset($certificates) && $certificates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        @php
                            $isSimulado = $certificate->type === 'simulado';
                            $metadata = $certificate->metadata ?? [];
                            $score = $metadata['score'] ?? null;
                            $performanceLevel = $metadata['performance_level'] ?? null;
                            $completionTime = $metadata['completion_time_formatted'] ?? null;
                            $pontosTotais = $metadata['pontos_totais'] ?? null;
                        @endphp
                        
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105 border {{ $isSimulado ? 'border-blue-200 dark:border-blue-800' : 'border-green-200 dark:border-green-800' }}">
                            <div class="p-6">
                                <!-- Header do Certificado -->
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 {{ $isSimulado ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-green-400 to-green-600' }} rounded-full flex items-center justify-center mr-4">
                                        @if($isSimulado)
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                                            {{ $certificate->title }}
                                        </h3>
                                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $certificate->type === 'simulado' ? 'Certificado de Simulado' : ucfirst($certificate->type) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Informações Específicas para Simulados -->
                                @if($isSimulado && $score !== null)
                                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Score:</span>
                                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $score }}%</span>
                                        </div>
                                        
                                        @if($performanceLevel)
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Performance:</span>
                                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400 capitalize">
                                                    @switch($performanceLevel)
                                                        @case('excelente')
                                                            ⭐ Excelente
                                                            @break
                                                        @case('muito_bom')
                                                            🌟 Muito Bom
                                                            @break
                                                        @case('bom')
                                                            👍 Bom
                                                            @break
                                                        @case('satisfatorio')
                                                            ✅ Satisfatório
                                                            @break
                                                        @default
                                                            🎯 Aprovado
                                                    @endswitch
                                                </span>
                                            </div>
                                        @endif
                                        
                                        @if($completionTime)
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Tempo:</span>
                                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $completionTime }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($pontosTotais)
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Pontos Ganhos:</span>
                                                <span class="text-sm font-bold text-yellow-600 dark:text-yellow-400">+{{ $pontosTotais }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Descrição -->
                                <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-4 line-clamp-2">
                                    {{ $certificate->description }}
                                </p>

                                <!-- Informações do Certificado -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4z"></path>
                                        </svg>
                                        Emitido em {{ $certificate->issued_at->format('d/m/Y') }}
                                    </div>
                                    
                                    @if($certificate->expires_at)
                                        <div class="flex items-center text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Válido até {{ $certificate->expires_at->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Ações -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('certificates.show', $certificate->id) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Visualizar
                                    </a>
                                    
                                    <a href="{{ route('certificates.verify', $certificate->verification_code) }}" 
                                       class="inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                @if($certificates->hasPages())
                    <div class="mt-8">
                        {{ $certificates->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-hcp-secondary-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                        Nenhum certificado encontrado
                    </h3>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                        Complete simulados, módulos e quizzes para ganhar certificados!
                    </p>
                    <a href="{{ route('simulados.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Fazer Simulados
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal para Solicitar Certificado -->
    <div id="requestCertificateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-hcp-secondary-900 dark:text-white">
                        Solicitar Certificado
                    </h3>
                    <button type="button" onclick="document.getElementById('requestCertificateModal').classList.add('hidden')" class="text-hcp-secondary-500 hover:text-hcp-secondary-700 dark:hover:text-hcp-secondary-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                    Selecione um curso ou quiz concluído para solicitar seu certificado. Apenas cursos concluídos 100% ou quizzes aprovados estão disponíveis.
                </p>
                
                <form action="{{ route('certificates.request') }}" method="POST">
                    @csrf
                    
                    <!-- Tabs -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 mb-4">
                        <div class="flex -mb-px">
                            <button type="button" onclick="switchTab('modules')" class="tab-button active-tab py-2 px-4 font-medium border-b-2 border-hcp-primary-500 text-hcp-primary-600 dark:text-hcp-primary-400" data-tab="modules">
                                Módulos
                            </button>
                            <button type="button" onclick="switchTab('quizzes')" class="tab-button py-2 px-4 font-medium border-b-2 border-transparent text-hcp-secondary-500 hover:text-hcp-secondary-700 dark:text-hcp-secondary-400 dark:hover:text-white" data-tab="quizzes">
                                Quizzes
                            </button>
                        </div>
                    </div>
                    
                    <!-- Conteúdo das Tabs -->
                    <div id="modules-tab" class="tab-content">
                        <div class="mb-4">
                            <label for="module_id" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Selecione um Módulo Concluído
                            </label>
                            <select name="module_id" id="module_id" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-md shadow-sm focus:ring-hcp-primary-500 focus:border-hcp-primary-500 dark:bg-hcp-secondary-700 dark:text-white">
                                <option value="">Selecione um módulo...</option>
                                @foreach($completedModules ?? [] as $module)
                                    <option value="{{ $module['id'] ?? '' }}">{{ $module['title'] ?? 'Módulo' }} ({{ $module['category'] ?? 'Geral' }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Apenas módulos concluídos 100% são exibidos.
                            </p>
                        </div>
                    </div>
                    
                    <div id="quizzes-tab" class="tab-content hidden">
                        <div class="mb-4">
                            <label for="quiz_id" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Selecione um Quiz Aprovado
                            </label>
                            <select name="quiz_id" id="quiz_id" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-md shadow-sm focus:ring-hcp-primary-500 focus:border-hcp-primary-500 dark:bg-hcp-secondary-700 dark:text-white">
                                <option value="">Selecione um quiz...</option>
                                @foreach($passedQuizzes ?? [] as $quiz)
                                    <option value="{{ $quiz['id'] ?? '' }}">{{ $quiz['title'] ?? 'Quiz' }} ({{ $quiz['score'] ?? 0 }}%)</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Apenas quizzes com aprovação são exibidos.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" 
                                onclick="document.getElementById('requestCertificateModal').classList.add('hidden')"
                                class="px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-md text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-md">
                            Solicitar Certificado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function switchTab(tabName) {
            // Esconder todos os conteúdos
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Mostrar o conteúdo selecionado
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Atualizar estilos dos botões
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active-tab', 'border-hcp-primary-500', 'text-hcp-primary-600', 'dark:text-hcp-primary-400');
                button.classList.add('border-transparent', 'text-hcp-secondary-500', 'hover:text-hcp-secondary-700', 'dark:text-hcp-secondary-400', 'dark:hover:text-white');
            });
            
            // Ativar o botão selecionado
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active-tab', 'border-hcp-primary-500', 'text-hcp-primary-600', 'dark:text-hcp-primary-400');
            document.querySelector(`[data-tab="${tabName}"]`).classList.remove('border-transparent', 'text-hcp-secondary-500', 'hover:text-hcp-secondary-700', 'dark:text-hcp-secondary-400', 'dark:hover:text-white');
        }
    </script>
</x-layouts.employee>