<x-layouts.app title="{{ $course->title }}">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 shadow-xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <!-- Course Info -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-4 mb-4">
                            <a href="{{ route('courses.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white p-2 rounded-lg transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div class="flex items-center space-x-3">
                                @php
                                    $typeColors = [
                                        'mandatory' => 'bg-red-500/20 border-red-500/30 text-red-200',
                                        'optional' => 'bg-blue-500/20 border-blue-500/30 text-blue-200',
                                        'certification' => 'bg-purple-500/20 border-purple-500/30 text-purple-200'
                                    ];
                                @endphp
                                <span class="{{ $typeColors[$course->type] ?? 'bg-gray-500/20 border-gray-500/30 text-gray-200' }} px-3 py-1 rounded-full text-sm font-medium border backdrop-blur-sm">
                                    {{ $course->type_label }}
                                </span>
                                <span class="bg-white/10 border border-white/20 text-white px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm">
                                    {{ $course->difficulty_label }}
                                </span>
                            </div>
                        </div>
                        
                        <h1 class="text-4xl font-bold text-white mb-4">{{ $course->title }}</h1>
                        
                        @if($course->short_description)
                            <p class="text-xl text-blue-100 mb-6">{{ $course->short_description }}</p>
                        @endif

                        <!-- Course Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/20">
                                <div class="text-lg font-bold text-white">{{ $stats['modules_count'] }}</div>
                                <div class="text-blue-100 text-sm">Módulos</div>
                            </div>
                            @if($stats['total_duration'] > 0)
                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/20">
                                    <div class="text-lg font-bold text-white">{{ number_format($stats['total_duration'], 1) }}h</div>
                                    <div class="text-blue-100 text-sm">Duração</div>
                                </div>
                            @endif
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/20">
                                <div class="text-lg font-bold text-white">{{ $stats['enrolled_users'] }}</div>
                                <div class="text-blue-100 text-sm">Inscritos</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/20">
                                <div class="text-lg font-bold text-white">{{ number_format($stats['completion_rate'], 1) }}%</div>
                                <div class="text-blue-100 text-sm">Conclusão</div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Thumbnail -->
                    <div class="lg:col-span-1">
                        <div class="relative">
                            @if($course->thumbnail)
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-2xl shadow-2xl">
                            @else
                                <div class="w-full h-64 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                            
                            @if($enrollment)
                                @php
                                    $progress = $enrollment->progress_percentage;
                                @endphp
                                @if($progress > 0)
                                    <div class="absolute bottom-4 left-4 right-4 bg-black/50 backdrop-blur-sm rounded-lg p-3">
                                        <div class="flex items-center justify-between text-white text-sm mb-2">
                                            <span>Seu Progresso</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-white/20 rounded-full h-2">
                                            <div class="bg-white rounded-full h-2 transition-all duration-300" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Sobre o Curso
                        </h2>
                        <div class="prose prose-lg max-w-none text-gray-700">
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>

                    <!-- Course Modules -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Conteúdo do Curso ({{ $modules->count() }} módulos)
                        </h2>

                        @if($modules->count() > 0)
                            <div class="space-y-4">
                                @foreach($modules as $index => $module)
                                    @php
                                        $moduleProgress = null;
                                        $isCompleted = false;
                                        if ($enrollment) {
                                            $moduleProgress = auth()->user()->progress()->where('module_id', $module->id)->first();
                                            $isCompleted = $moduleProgress && $moduleProgress->status === 'completed';
                                        }
                                    @endphp
                                    
                                    <div class="border border-gray-200 rounded-xl p-6 hover:border-indigo-300 transition-colors {{ $isCompleted ? 'bg-green-50 border-green-200' : '' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    @if($isCompleted)
                                                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                            <span class="text-indigo-600 font-bold">{{ $index + 1 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 {{ $isCompleted ? 'line-through text-green-700' : '' }}">
                                                        {{ $module->title }}
                                                    </h3>
                                                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($module->description, 120) }}</p>
                                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
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
                                                        @if($isCompleted && $moduleProgress)
                                                            <span class="text-green-600 font-medium">
                                                                Concluído em {{ $moduleProgress->completed_at->format('d/m/Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($enrollment && $enrollment->status !== 'enrolled')
                                                <div class="flex-shrink-0">
                                                    @if($isCompleted)
                                                        <a href="{{ route('modules.show', $module) }}" class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg font-medium transition-colors">
                                                            Revisar
                                                        </a>
                                                    @else
                                                        <a href="{{ route('modules.show', $module) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                            {{ $moduleProgress ? 'Continuar' : 'Iniciar' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum módulo disponível</h3>
                                <p class="text-gray-600">O conteúdo deste curso será adicionado em breve.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Enrollment Status -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        @if($enrollment)
                            @php
                                $statusInfo = [
                                    'enrolled' => ['color' => 'blue', 'text' => 'Inscrito', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'in_progress' => ['color' => 'yellow', 'text' => 'Em Progresso', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'completed' => ['color' => 'green', 'text' => 'Concluído', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'dropped' => ['color' => 'red', 'text' => 'Abandonado', 'icon' => 'M6 18L18 6M6 6l12 12']
                                ];
                                $status = $statusInfo[$enrollment->status] ?? $statusInfo['enrolled'];
                            @endphp
                            
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-{{ $status['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-{{ $status['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $status['icon'] }}"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $status['text'] }}</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Inscrito em {{ $enrollment->enrolled_at->format('d/m/Y') }}
                                </p>
                            </div>

                            @if($enrollment->status === 'completed')
                                <a href="{{ route('courses.certificate', $course) }}" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block mb-4">
                                    Ver Certificado
                                </a>
                                <a href="{{ route('courses.progress', $course) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition-colors text-center block">
                                    Ver Progresso
                                </a>
                            @elseif($enrollment->status === 'in_progress')
                                <a href="{{ route('courses.progress', $course) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block mb-4">
                                    Ver Progresso
                                </a>
                                @if($modules->count() > 0)
                                    @php
                                        $nextModule = $modules->first(function($module) {
                                            $progress = auth()->user()->progress()->where('module_id', $module->id)->first();
                                            return !$progress || $progress->status !== 'completed';
                                        });
                                    @endphp
                                    @if($nextModule)
                                        <a href="{{ route('modules.show', $nextModule) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block">
                                            Continuar Aprendizado
                                        </a>
                                    @endif
                                @endif
                            @else
                                <form method="POST" action="{{ route('courses.start', $course) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                        Iniciar Curso
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Pronto para começar?</h3>
                                <p class="text-gray-600 text-sm mt-1">Inscreva-se neste curso e comece a aprender</p>
                            </div>

                            @if($canEnroll)
                                <form method="POST" action="{{ route('courses.enroll', $course) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                        Inscrever-se no Curso
                                    </button>
                                </form>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-medium text-yellow-800">Pré-requisitos não atendidos</h4>
                                            <p class="text-sm text-yellow-700 mt-1">Você precisa completar outros cursos antes de se inscrever neste.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Course Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Curso</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipo:</span>
                                <span class="font-medium text-gray-900">{{ $course->type_label }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dificuldade:</span>
                                <span class="font-medium text-gray-900">{{ $course->difficulty_label }}</span>
                            </div>
                            @if($course->duration_hours > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duração:</span>
                                    <span class="font-medium text-gray-900">{{ $course->duration_hours }} horas</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Módulos:</span>
                                <span class="font-medium text-gray-900">{{ $modules->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($course->tags && count($course->tags) > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($course->tags as $tag)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>