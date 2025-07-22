<x-layouts.app title="Cursos Disponíveis">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 shadow-xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-4">Cursos de Aprendizado</h1>
                    <p class="text-xl text-blue-100 mb-8">Desenvolva suas habilidades com nossos cursos estruturados</p>
                    
                    <!-- Estatísticas -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white">{{ $stats['total_courses'] }}</div>
                            <div class="text-blue-100 text-sm">Cursos Disponíveis</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-green-300">{{ $stats['enrolled_courses'] }}</div>
                            <div class="text-blue-100 text-sm">Inscritos</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-yellow-300">{{ $stats['in_progress_courses'] }}</div>
                            <div class="text-blue-100 text-sm">Em Progresso</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-purple-300">{{ $stats['completed_courses'] }}</div>
                            <div class="text-blue-100 text-sm">Concluídos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Cursos Inscritos -->
            @if($enrolledCourses->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Meus Cursos
                        </h2>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $enrolledCourses->count() }} {{ $enrolledCourses->count() === 1 ? 'curso' : 'cursos' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($enrolledCourses as $course)
                            @php
                                $enrollment = $course->getStatusForUser(auth()->user());
                                $progress = $course->getProgressForUser(auth()->user());
                                $statusColors = [
                                    'enrolled' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'dropped' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                                <!-- Thumbnail -->
                                <div class="relative h-48 bg-gradient-to-br from-blue-500 to-indigo-600">
                                    @if($course->thumbnail)
                                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-4 right-4">
                                        <span class="{{ $statusColors[$enrollment] ?? 'bg-gray-100 text-gray-800' }} px-3 py-1 rounded-full text-xs font-medium">
                                            {{ ucfirst($enrollment) }}
                                        </span>
                                    </div>

                                    <!-- Progress Bar -->
                                    @if($progress > 0)
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/20 p-3">
                                            <div class="flex items-center justify-between text-white text-sm mb-1">
                                                <span>Progresso</span>
                                                <span>{{ $progress }}%</span>
                                            </div>
                                            <div class="w-full bg-white/20 rounded-full h-2">
                                                <div class="bg-white rounded-full h-2 transition-all duration-300" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-xl font-bold text-gray-900 line-clamp-2">{{ $course->title }}</h3>
                                        <span class="ml-2 bg-{{ $course->type === 'mandatory' ? 'red' : ($course->type === 'certification' ? 'purple' : 'blue') }}-100 text-{{ $course->type === 'mandatory' ? 'red' : ($course->type === 'certification' ? 'purple' : 'blue') }}-800 px-2 py-1 rounded-md text-xs font-medium">
                                            {{ $course->type_label }}
                                        </span>
                                    </div>

                                    @if($course->short_description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->short_description }}</p>
                                    @endif

                                    <!-- Metadata -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                        <div class="flex items-center space-x-4">
                                            @if($course->duration_hours > 0)
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $course->duration_hours }}h</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span>{{ $course->difficulty_label }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div>{{ $course->modules()->count() }} módulos</div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        @if($enrollment === 'completed')
                                            <a href="{{ route('courses.certificate', $course) }}" 
                                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                                Ver Certificado
                                            </a>
                                        @elseif($enrollment === 'in_progress')
                                            <a href="{{ route('courses.progress', $course) }}" 
                                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                                Continuar
                                            </a>
                                        @else
                                            <a href="{{ route('courses.start', $course) }}" 
                                               class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                                Iniciar Curso
                                            </a>
                                        @endif
                                        <a href="{{ route('courses.show', $course) }}" 
                                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors">
                                            Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cursos Disponíveis -->
            @if($availableCourses->count() > 0)
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Cursos Disponíveis
                        </h2>
                        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $availableCourses->count() }} {{ $availableCourses->count() === 1 ? 'curso' : 'cursos' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($availableCourses as $course)
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-indigo-200">
                                <!-- Thumbnail -->
                                <div class="relative h-48 bg-gradient-to-br from-indigo-500 to-purple-600">
                                    @if($course->thumbnail)
                                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Type Badge -->
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-{{ $course->type === 'mandatory' ? 'red' : ($course->type === 'certification' ? 'purple' : 'blue') }}-500/20 border border-{{ $course->type === 'mandatory' ? 'red' : ($course->type === 'certification' ? 'purple' : 'blue') }}-500/30 text-white px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm">
                                            {{ $course->type_label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">{{ $course->title }}</h3>

                                    @if($course->short_description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->short_description }}</p>
                                    @endif

                                    <!-- Metadata -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                        <div class="flex items-center space-x-4">
                                            @if($course->duration_hours > 0)
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $course->duration_hours }}h</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span>{{ $course->difficulty_label }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div>{{ $course->modules()->count() }} módulos</div>
                                        </div>
                                    </div>

                                    <!-- Tags -->
                                    @if($course->tags && count($course->tags) > 0)
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach(array_slice($course->tags, 0, 2) as $tag)
                                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-md text-xs">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                            @if(count($course->tags) > 2)
                                                <span class="text-gray-400 text-xs">+{{ count($course->tags) - 2 }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('courses.show', $course) }}" 
                                           class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                            Ver Curso
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if($enrolledCourses->count() === 0 && $availableCourses->count() === 0)
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">Nenhum curso disponível</h3>
                    <p class="text-gray-600 mb-6">Novos cursos serão adicionados em breve. Fique atento!</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>