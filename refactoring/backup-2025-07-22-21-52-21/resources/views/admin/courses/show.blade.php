<x-layouts.admin title="{{ $course->title }}">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-16 w-16 rounded-xl overflow-hidden bg-white/10 backdrop-blur-sm">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-16 w-16 object-cover">
                            @else
                                <div class="h-16 w-16 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center space-x-3">
                                <h1 class="text-3xl font-bold text-white">{{ $course->title }}</h1>
                                @if($course->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                        <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                                        Inativo
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center mt-2 space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300">
                                    {{ $course->difficulty_label }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                                    {{ $course->duration_hours }} horas
                                </span>
                                @if($course->type === 'mandatory')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                        Obrigatório
                                    </span>
                                @elseif($course->type === 'optional')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                                        Opcional
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-300">
                                        Certificação
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.courses.edit', $course) }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Editar</span>
                        </a>
                        <a href="{{ route('admin.courses.modules', $course) }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Gerenciar Módulos</span>
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Voltar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Estatísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total de Módulos -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Total de Módulos</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['modules_count'] }}</p>
                        </div>
                        <div class="bg-blue-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Módulos Ativos -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Módulos Ativos</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['active_modules'] }}</p>
                        </div>
                        <div class="bg-green-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Inscrições -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Inscrições</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['total_enrollments'] }}</p>
                        </div>
                        <div class="bg-purple-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Taxa de Conclusão -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Taxa de Conclusão</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['completion_rate'] }}%</p>
                        </div>
                        <div class="bg-yellow-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Duração Total -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Duração Total</p>
                            <p class="text-white text-3xl font-bold">{{ number_format($stats['total_duration'], 1) }}h</p>
                        </div>
                        <div class="bg-red-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalhes do Curso -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Informações do Curso -->
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6 border-b border-slate-700/50">
                        <h3 class="text-xl font-bold text-white">Informações do Curso</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-invert max-w-none">
                            <h4 class="text-lg font-semibold text-white mb-2">Descrição</h4>
                            <div class="text-slate-300 mb-6">
                                {!! nl2br(e($course->description)) !!}
                            </div>

                            @if($course->requirements)
                                <h4 class="text-lg font-semibold text-white mb-2">Requisitos</h4>
                                <ul class="list-disc pl-5 text-slate-300 mb-6">
                                    @foreach($course->requirements as $requirement)
                                        <li>{{ $requirement['description'] ?? 'Requisito não especificado' }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($course->tags && count($course->tags) > 0)
                                <h4 class="text-lg font-semibold text-white mb-2">Tags</h4>
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @foreach($course->tags as $tag)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-700 text-slate-300">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6 border-b border-slate-700/50">
                        <h3 class="text-xl font-bold text-white">Ações Rápidas</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('admin.courses.modules', $course) }}" class="flex items-center p-4 bg-slate-700/30 hover:bg-slate-700/50 rounded-xl transition-colors">
                            <div class="bg-blue-500/20 p-3 rounded-xl mr-4">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-medium">Gerenciar Módulos</h4>
                                <p class="text-slate-400 text-sm">Adicionar, remover ou reordenar módulos</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.courses.enrollments', $course) }}" class="flex items-center p-4 bg-slate-700/30 hover:bg-slate-700/50 rounded-xl transition-colors">
                            <div class="bg-purple-500/20 p-3 rounded-xl mr-4">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-medium">Gerenciar Inscrições</h4>
                                <p class="text-slate-400 text-sm">Ver e adicionar usuários ao curso</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.courses.reports', $course) }}" class="flex items-center p-4 bg-slate-700/30 hover:bg-slate-700/50 rounded-xl transition-colors">
                            <div class="bg-green-500/20 p-3 rounded-xl mr-4">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-medium">Relatórios</h4>
                                <p class="text-slate-400 text-sm">Estatísticas e análises do curso</p>
                            </div>
                        </a>

                        <form action="{{ route('admin.courses.toggle-active', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center p-4 bg-slate-700/30 hover:bg-slate-700/50 rounded-xl transition-colors">
                                <div class="{{ $course->is_active ? 'bg-red-500/20' : 'bg-green-500/20' }} p-3 rounded-xl mr-4">
                                    @if($course->is_active)
                                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-white font-medium">{{ $course->is_active ? 'Desativar Curso' : 'Ativar Curso' }}</h4>
                                    <p class="text-slate-400 text-sm">{{ $course->is_active ? 'Tornar o curso indisponível' : 'Tornar o curso disponível' }}</p>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este curso? Esta ação não pode ser desfeita e removerá todas as inscrições associadas.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center p-4 bg-slate-700/30 hover:bg-slate-700/50 rounded-xl transition-colors">
                                <div class="bg-red-500/20 p-3 rounded-xl mr-4">
                                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-medium">Excluir Curso</h4>
                                    <p class="text-slate-400 text-sm">Remover permanentemente este curso</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista de Módulos -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                <div class="p-6 border-b border-slate-700/50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Módulos do Curso</h3>
                    <a href="{{ route('admin.courses.modules', $course) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Adicionar Módulo</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-700/30 border-b border-slate-700/50">
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ordem</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Módulo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Duração</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @forelse($modules as $module)
                                <tr class="hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">{{ $module->order_index }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden bg-slate-700">
                                                @if($module->thumbnail)
                                                    <img src="{{ $module->thumbnail_url }}" alt="{{ $module->title }}" class="h-10 w-10 object-cover">
                                                @else
                                                    <div class="h-10 w-10 flex items-center justify-center bg-blue-500/20">
                                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-white">{{ $module->title }}</div>
                                                <div class="text-xs text-slate-400">{{ Str::limit($module->description, 60) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($module->content_type === 'video')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                                                Vídeo
                                            </span>
                                        @elseif($module->content_type === 'text')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300">
                                                Texto
                                            </span>
                                        @elseif($module->content_type === 'quiz')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-300">
                                                Quiz
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300">
                                                Misto
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">{{ $module->duration_minutes }} min</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($module->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                                <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.modules.show', $module) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.modules.edit', $module) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.courses.modules.remove', ['course' => $course->id, 'module' => $module->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-lg font-medium">Nenhum módulo encontrado</p>
                                            <p class="text-sm mt-1">Este curso ainda não possui módulos</p>
                                            <a href="{{ route('admin.courses.modules', $course) }}" class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-xl font-medium transition-all duration-300">
                                                Adicionar Módulo
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>