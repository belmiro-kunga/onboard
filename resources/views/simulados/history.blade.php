<x-layouts.employee title="Histórico de Simulados - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        Histórico de Simulados
                    </h1>
                    <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Acompanhe seu progresso e desempenho nos simulados
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('simulados.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver Simulados
                    </a>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Tentativas</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['total_tentativas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Aprovações</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['tentativas_aprovadas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Média de Score</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ number_format($stats['media_score'] ?? 0, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos Ganhos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['pontos_ganhos'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Tentativas -->
            @if(isset($tentativas) && $tentativas->count() > 0)
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                            <thead class="bg-hcp-secondary-50 dark:bg-hcp-secondary-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Simulado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Data
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Score
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Tempo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-hcp-secondary-800 divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                                @foreach($tentativas as $tentativa)
                                    <tr class="hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-hcp-primary-100 dark:bg-hcp-primary-900/30 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-hcp-primary-600 dark:text-hcp-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                        {{ $tentativa->simulado->titulo }}
                                                    </div>
                                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                        {{ $tentativa->simulado->categoria_formatada }} - {{ $tentativa->simulado->nivel_formatado }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-hcp-secondary-900 dark:text-white">
                                                {{ $tentativa->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                {{ $tentativa->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tentativa->status === 'completed')
                                                <div class="text-sm font-medium {{ $tentativa->score >= $tentativa->simulado->passing_score ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $tentativa->score }}%
                                                </div>
                                            @else
                                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    -
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tentativa->status === 'completed')
                                                @if($tentativa->score >= $tentativa->simulado->passing_score)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                        Aprovado
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                                        Não aprovado
                                                    </span>
                                                @endif
                                            @elseif($tentativa->status === 'in_progress')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                                    Em andamento
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                                    {{ ucfirst($tentativa->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hcp-secondary-900 dark:text-white">
                                            @if($tentativa->tempo_gasto)
                                                {{ gmdate('H:i:s', $tentativa->tempo_gasto) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($tentativa->status === 'completed')
                                                <a href="{{ route('simulados.result', ['id' => $tentativa->simulado_id, 'tentativa' => $tentativa->id]) }}" 
                                                   class="text-hcp-primary-600 hover:text-hcp-primary-900 dark:text-hcp-primary-400 dark:hover:text-hcp-primary-300 mr-3">
                                                    Resultado
                                                </a>
                                                <a href="{{ route('simulados.report', ['id' => $tentativa->simulado_id, 'tentativa' => $tentativa->id]) }}" 
                                                   class="text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-hcp-secondary-300">
                                                    Relatório
                                                </a>
                                            @elseif($tentativa->status === 'in_progress')
                                                <a href="{{ route('simulados.attempt', ['id' => $tentativa->simulado_id, 'tentativa' => $tentativa->id]) }}" 
                                                   class="text-hcp-primary-600 hover:text-hcp-primary-900 dark:text-hcp-primary-400 dark:hover:text-hcp-primary-300">
                                                    Continuar
                                                </a>
                                            @else
                                                <span class="text-hcp-secondary-400 dark:text-hcp-secondary-600">
                                                    Indisponível
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($tentativas->hasPages())
                        <div class="px-6 py-4 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            {{ $tentativas->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-hcp-secondary-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400 dark:text-hcp-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-hcp-secondary-900 dark:text-white mb-2">Nenhuma tentativa encontrada</h3>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                        Você ainda não realizou nenhum simulado. Comece agora mesmo!
                    </p>
                    <a href="{{ route('simulados.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver Simulados
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.employee>