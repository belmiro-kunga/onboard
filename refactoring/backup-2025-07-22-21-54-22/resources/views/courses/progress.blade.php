<x-layouts.app title="Progresso do Curso">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 shadow-xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center space-x-4 mb-6">
                    <a href="{{ route('courses.show', $course) }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white p-2 rounded-lg transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $course->title }}</h1>
                        <p class="text-blue-100 mt-1">Acompanhe seu progresso no curso</p>
                    </div>
                </div>

                <!-- Progress Overview -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-white">Progresso Geral</h2>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-white">{{ $enrollment->progress_percentage }}%</div>
                            <div class="text-blue-100 text-sm">Concluído</div>
                        </div>
                    </div>
                    
                    <div class="w-full bg-white/20 rounded-full h-4 mb-4">
                        <div class="bg-white rounded-full h-4 transition-all duration-500" style="width: {{ $enrollment->progress_percentage }}%"></div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-lg font-bold text-white">{{ $modules->count() }}</div>
                            <div class="text-blue-100 text-sm">Total de Módulos</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-300">
                                {{ collect($moduleProgress)->where('status', 'completed')->count() }}
                            </div>
                            <div class="text-blue-100 text-sm">Concluídos</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-yellow-300">
                                {{ collect($moduleProgress)->where('status', 'in_progress')->count() }}
                            </div>
                            <div class="text-blue-100 text-sm">Em Progresso</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Module Progress -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Progresso por Módulo</h2>
                        
                        <div class="space-y-6">
                            @foreach($modules as $index => $module)
                                @php
                                    $progress = $moduleProgress[$module->id] ?? ['status' => 'not_started', 'percentage' => 0, 'completed_at' => null];
                                    $statusColors = [
                                        'completed' => 'bg-green-500',
                                        'in_progress' => 'bg-blue-500',
                                        'not_started' => 'bg-gray-300'
                                    ];
                                    $statusTextColors = [
                                        'completed' => 'text-green-700',
                                        'in_progress' => 'text-blue-700',
                                        'not_started' => 'text-gray-500'
                                    ];
                                    $statusLabels = [
                                        'completed' => 'Concluído',
                                        'in_progress' => 'Em Progresso',
                                        'not_started' => 'Não Iniciado'
                                    ];
                                @endphp
                                
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-colors {{ $progress['status'] === 'completed' ? 'bg-green-50' : '' }}">
                                    <div class="flex items-start space-x-4">
                                        <!-- Module Number/Status -->
                                        <div class="flex-shrink-0">
                                            @if($progress['status'] === 'completed')
                                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                            @elseif($progress['status'] === 'in_progress')
                                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 font-bold">{{ $index + 1 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Module Info -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $module->title }}</h3>
                                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$progress['status']] }} text-white">
                                                    {{ $statusLabels[$progress['status']] }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($module->description, 120) }}</p>
                                            
                                            <!-- Progress Bar -->
                                            @if($progress['status'] !== 'not_started')
                                                <div class="mb-3">
                                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                                        <span>Progresso</span>
                                                        <span>{{ $progress['percentage'] }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="{{ $statusColors[$progress['status']] }} h-2 rounded-full transition-all duration-300" style="width: {{ $progress['percentage'] }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Module Meta -->
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <div class="flex items-center space-x-4">
                                                    @if($module->duration_minutes > 0)
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ $module->duration_minutes }} min
                                                        </span>
                                                    @endif
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                                        </svg>
                                                        {{ ucfirst($module->content_type) }}
                                                    </span>
                                                </div>
                                                
                                                @if($progress['completed_at'])
                                                    <span class="text-green-600 font-medium">
                                                        Concluído em {{ $progress['completed_at']->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Action Button -->
                                            <div class="mt-4">
                                                @if($progress['status'] === 'completed')
                                                    <a href="{{ route('modules.show', $module) }}" class="inline-flex items-center bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg font-medium transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Revisar
                                                    </a>
                                                @elseif($progress['status'] === 'in_progress')
                                                    <a href="{{ route('modules.show', $module) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10v4a1 1 0 01-1 1H7a1 1 0 01-1-1v-4a1 1 0 011-1h1m2 0V9a1 1 0 011-1h1a1 1 0 011 1v1m-2 0h2"/>
                                                        </svg>
                                                        Continuar
                                                    </a>
                                                @else
                                                    <a href="{{ route('modules.show', $module) }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10v4a1 1 0 01-1 1H7a1 1 0 01-1-1v-4a1 1 0 011-1h1m2 0V9a1 1 0 011-1h1a1 1 0 011 1v1m-2 0h2"/>
                                                        </svg>
                                                        Iniciar
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Course Status -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status do Curso</h3>
                        
                        @php
                            $statusInfo = [
                                'enrolled' => ['color' => 'blue', 'text' => 'Inscrito', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'in_progress' => ['color' => 'yellow', 'text' => 'Em Progresso', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'completed' => ['color' => 'green', 'text' => 'Concluído', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'dropped' => ['color' => 'red', 'text' => 'Abandonado', 'icon' => 'M6 18L18 6M6 6l12 12']
                            ];
                            $status = $statusInfo[$enrollment->status] ?? $statusInfo['enrolled'];
                        @endphp
                        
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-{{ $status['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-{{ $status['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $status['icon'] }}"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $status['text'] }}</h4>
                            <p class="text-gray-600 text-sm mt-1">
                                Inscrito em {{ $enrollment->enrolled_at->format('d/m/Y') }}
                            </p>
                        </div>

                        @if($enrollment->started_at)
                            <div class="border-t border-gray-200 pt-4">
                                <div class="text-sm text-gray-600">
                                    <p><strong>Iniciado em:</strong> {{ $enrollment->started_at->format('d/m/Y H:i') }}</p>
                                    @if($enrollment->completed_at)
                                        <p><strong>Concluído em:</strong> {{ $enrollment->completed_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Tempo total:</strong> {{ $enrollment->started_at->diffInDays($enrollment->completed_at) }} dias</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Course Statistics -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Módulos Concluídos:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ collect($moduleProgress)->where('status', 'completed')->count() }}/{{ $modules->count() }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Progresso Geral:</span>
                                <span class="font-semibold text-gray-900">{{ $enrollment->progress_percentage }}%</span>
                            </div>
                            
                            @if($enrollment->started_at)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tempo Decorrido:</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $enrollment->started_at->diffInDays(now()) }} dias
                                    </span>
                                </div>
                            @endif
                            
                            @php
                                $totalDuration = $modules->sum('duration_minutes');
                                $completedDuration = $modules->whereIn('id', collect($moduleProgress)->where('status', 'completed')->keys())->sum('duration_minutes');
                            @endphp
                            
                            @if($totalDuration > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tempo de Estudo:</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ number_format($completedDuration / 60, 1) }}h / {{ number_format($totalDuration / 60, 1) }}h
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Next Steps -->
                    @if($enrollment->status !== 'completed')
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Próximos Passos</h3>
                            
                            @php
                                $nextModule = $modules->first(function($module) use ($moduleProgress) {
                                    $progress = $moduleProgress[$module->id] ?? ['status' => 'not_started'];
                                    return $progress['status'] !== 'completed';
                                });
                            @endphp
                            
                            @if($nextModule)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-900 mb-2">Próximo Módulo:</h4>
                                    <p class="text-blue-800 text-sm mb-3">{{ $nextModule->title }}</p>
                                    <a href="{{ route('modules.show', $nextModule) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                        Continuar Aprendizado
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Parabéns!</h3>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600 mb-4">Você concluiu este curso com sucesso!</p>
                                <a href="{{ route('courses.certificate', $course) }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    Ver Certificado
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>