<x-layouts.employee title="História da Empresa">
    <!-- Hero Section com Background Animado -->
    <div class="relative min-h-screen overflow-hidden">
        <!-- Background Dinâmico -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.08"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
            
            <!-- Elementos Flutuantes -->
            <div class="absolute top-20 left-10 w-32 h-32 bg-hcp-500/10 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute top-60 right-20 w-24 h-24 bg-purple-500/10 rounded-full blur-xl animate-pulse delay-1000"></div>
            <div class="absolute bottom-40 left-1/3 w-20 h-20 bg-blue-500/10 rounded-full blur-xl animate-pulse delay-2000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header Moderno -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/80 text-sm font-medium mb-8 border border-white/20">
                    <div class="w-2 h-2 bg-hcp-500 rounded-full mr-2 animate-pulse"></div>
                    Nossa Jornada • {{ $stats['years_active'] }} Anos de História
                </div>
                
                <h1 class="text-6xl md:text-7xl font-bold text-white mb-8 leading-tight">
                    <span class="bg-gradient-to-r from-white via-hcp-200 to-white bg-clip-text text-transparent">
                        História da
                    </span>
                    <br>
                    <span class="bg-gradient-to-r from-hcp-400 via-hcp-500 to-hcp-600 bg-clip-text text-transparent">
                        Hemera Capital
                    </span>
                </h1>
                
                <p class="text-xl text-white/70 max-w-3xl mx-auto mb-12 leading-relaxed">
                    Descubra os momentos que definiram nossa trajetória, desde a fundação até os dias atuais. 
                    Uma jornada de inovação, crescimento e conquistas no mercado financeiro.
                </p>

                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_events'] }}</div>
                        <div class="text-white/70 text-sm font-medium">Eventos Históricos</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">{{ $stats['years_active'] }}</div>
                        <div class="text-white/70 text-sm font-medium">Anos de Mercado</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">{{ $stats['major_milestones'] }}</div>
                        <div class="text-white/70 text-sm font-medium">Marcos Importantes</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">{{ $stats['categories_count'] }}</div>
                        <div class="text-white/70 text-sm font-medium">Categorias</div>
                    </div>
                </div>

                <!-- Filtros de Categoria -->
                <div class="flex flex-wrap justify-center gap-3 mb-12">
                    <button class="category-filter active bg-white/20 backdrop-blur-md text-white px-6 py-3 rounded-xl border border-white/30 hover:bg-white/30 transition-all duration-300 font-medium"
                            data-category="all">
                        <div class="flex items-center space-x-2">
                            <x-icon name="grid" size="sm" />
                            <span>Todos os Eventos</span>
                        </div>
                    </button>
                    
                    @foreach($categories as $key => $category)
                        <button class="category-filter bg-white/10 backdrop-blur-md text-white/80 px-6 py-3 rounded-xl border border-white/20 hover:bg-white/20 hover:text-white transition-all duration-300 font-medium"
                                data-category="{{ $key }}">
                            <div class="flex items-center space-x-2">
                                <x-icon name="{{ $category['icon'] }}" size="sm" />
                                <span>{{ $category['name'] }}</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Linha do Tempo Interativa -->
            <div class="relative">
                <!-- Linha Central -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-gradient-to-b from-hcp-500 via-hcp-400 to-hcp-500 h-full rounded-full shadow-lg"></div>
                
                <!-- Eventos da Timeline -->
                <div class="timeline-container space-y-16" id="timeline-events">
                    @foreach($events as $index => $event)
                        <div class="timeline-event relative flex items-center {{ $index % 2 === 0 ? 'justify-start' : 'justify-end' }}"
                             data-category="{{ $event['category'] }}"
                             data-year="{{ $event['year'] }}"
                             data-aos="fade-{{ $index % 2 === 0 ? 'right' : 'left' }}"
                             data-aos-delay="{{ ($index % 4) * 100 }}">
                            
                            <!-- Card do Evento -->
                            <div class="timeline-card bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 max-w-lg {{ $index % 2 === 0 ? 'mr-8' : 'ml-8' }} hover:scale-105 transition-all duration-500 cursor-pointer group"
                                 onclick="showEventDetails({{ $event['id'] }})">
                                
                                <!-- Header do Card -->
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-xl group-hover:scale-110 transition-transform duration-300"
                                             style="background: linear-gradient(135deg, {{ $event['color'] }}, {{ $event['color'] }}dd);">
                                            <x-icon name="{{ $event['icon'] }}" size="lg" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-white/60 mb-1">
                                                {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs px-3 py-1 rounded-full text-white font-medium"
                                                 style="background-color: {{ $event['color'] }}40; border: 1px solid {{ $event['color'] }}60;">
                                                {{ $categories[$event['category']]['name'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($event['importance'] === 'high')
                                        <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse shadow-lg"></div>
                                    @endif
                                </div>
                                
                                <!-- Conteúdo do Card -->
                                <div class="mb-6">
                                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-hcp-200 transition-colors duration-300">
                                        {{ $event['title'] }}
                                    </h3>
                                    <p class="text-white/80 leading-relaxed text-sm">
                                        {{ $event['description'] }}
                                    </p>
                                </div>
                                
                                <!-- Conquistas -->
                                @if(isset($event['achievements']) && count($event['achievements']) > 0)
                                    <div class="mb-6">
                                        <h4 class="text-sm font-semibold text-white/90 mb-3">Principais Conquistas:</h4>
                                        <div class="space-y-2">
                                            @foreach(array_slice($event['achievements'], 0, 2) as $achievement)
                                                <div class="flex items-center text-xs text-white/70">
                                                    <div class="w-1.5 h-1.5 bg-hcp-400 rounded-full mr-2"></div>
                                                    {{ $achievement }}
                                                </div>
                                            @endforeach
                                            @if(count($event['achievements']) > 2)
                                                <div class="text-xs text-hcp-300 font-medium">
                                                    +{{ count($event['achievements']) - 2 }} mais...
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Call to Action -->
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-white/60">
                                        {{ $event['year'] }}
                                    </div>
                                    <div class="flex items-center text-hcp-300 text-sm font-medium group-hover:text-hcp-200 transition-colors duration-300">
                                        <span class="mr-2">Ver detalhes</span>
                                        <x-icon name="arrow-right" size="sm" class="group-hover:translate-x-1 transition-transform duration-300" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Indicador Central -->
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-6 h-6 rounded-full border-4 border-white shadow-xl z-10"
                                 style="background-color: {{ $event['color'] }};">
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Indicador de Final -->
                <div class="flex justify-center mt-16">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-8 py-4 border border-white/20">
                        <div class="flex items-center space-x-3 text-white/70">
                            <div class="w-3 h-3 bg-hcp-500 rounded-full animate-pulse"></div>
                            <span class="font-medium">Nossa história continua...</span>
                            <div class="w-3 h-3 bg-hcp-500 rounded-full animate-pulse delay-500"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes do Evento -->
    <div id="event-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div id="modal-content">
                <!-- Conteúdo será inserido via JavaScript -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Dados dos eventos
        const events = @json($events);
        const categories = @json($categories);
        
        // Estado atual
        let currentFilter = 'all';
        let filteredEvents = events;

        /**
         * Filtrar eventos por categoria
         */
        function filterEvents(category) {
            currentFilter = category;
            
            // Atualizar botões
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.remove('active', 'bg-white/20');
                btn.classList.add('bg-white/10', 'text-white/80');
                
                if (btn.dataset.category === category) {
                    btn.classList.add('active', 'bg-white/20');
                    btn.classList.remove('bg-white/10', 'text-white/80');
                    btn.classList.add('text-white');
                }
            });
            
            // Filtrar eventos
            const timelineEvents = document.querySelectorAll('.timeline-event');
            
            timelineEvents.forEach((event, index) => {
                const eventCategory = event.dataset.category;
                const shouldShow = category === 'all' || eventCategory === category;
                
                if (shouldShow) {
                    event.style.display = 'flex';
                    // Animar entrada
                    setTimeout(() => {
                        event.style.opacity = '1';
                        event.style.transform = 'translateY(0)';
                    }, index * 50);
                } else {
                    event.style.opacity = '0';
                    event.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        event.style.display = 'none';
                    }, 300);
                }
            });
        }

        /**
         * Mostrar detalhes do evento
         */
        function showEventDetails(eventId) {
            const event = events.find(e => e.id === eventId);
            if (!event) return;
            
            const modal = document.getElementById('event-modal');
            const modalContent = document.getElementById('modal-content');
            
            modalContent.innerHTML = `
                <div class="p-8">
                    <!-- Header do Modal -->
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center space-x-6">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white shadow-2xl"
                                 style="background: linear-gradient(135deg, ${event.color}, ${event.color}dd);">
                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                    <!-- Ícone será inserido aqui -->
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white/60 mb-2">
                                    ${new Date(event.date).toLocaleDateString('pt-BR')}
                                </div>
                                <h2 class="text-3xl font-bold text-white mb-2">
                                    ${event.title}
                                </h2>
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-white font-medium text-sm"
                                     style="background-color: ${event.color}40; border: 1px solid ${event.color}60;">
                                    ${categories[event.category].name}
                                </div>
                            </div>
                        </div>
                        
                        <button onclick="closeEventModal()" 
                                class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center text-white/70 hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Conteúdo Principal -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Descrição Detalhada -->
                        <div class="lg:col-span-2">
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10 mb-6">
                                <h3 class="text-lg font-bold text-white mb-4">Sobre este Marco</h3>
                                <p class="text-white/80 leading-relaxed">
                                    ${event.details}
                                </p>
                            </div>
                            
                            <!-- Conquistas Detalhadas -->
                            ${event.achievements ? `
                                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                    <h3 class="text-lg font-bold text-white mb-4">Principais Conquistas</h3>
                                    <div class="space-y-3">
                                        ${event.achievements.map(achievement => `
                                            <div class="flex items-start p-3 bg-white/5 rounded-lg border border-white/10">
                                                <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                                    <svg class="w-3 h-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-white/80">${achievement}</span>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        
                        <!-- Sidebar com Informações -->
                        <div class="space-y-6">
                            <!-- Informações Básicas -->
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <h3 class="text-lg font-bold text-white mb-4">Informações</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-white/70">Data</span>
                                        <span class="text-white font-medium">${new Date(event.date).toLocaleDateString('pt-BR')}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white/70">Ano</span>
                                        <span class="text-white font-medium">${event.year}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white/70">Categoria</span>
                                        <span class="text-white font-medium">${categories[event.category].name}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white/70">Importância</span>
                                        <div class="flex items-center">
                                            ${event.importance === 'high' ? 
                                                '<div class="w-2 h-2 bg-red-400 rounded-full mr-1"></div><span class="text-red-400 font-medium">Alta</span>' :
                                                event.importance === 'medium' ?
                                                '<div class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></div><span class="text-yellow-400 font-medium">Média</span>' :
                                                '<div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div><span class="text-green-400 font-medium">Normal</span>'
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Navegação -->
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <h3 class="text-lg font-bold text-white mb-4">Navegação</h3>
                                <div class="space-y-3">
                                    <button onclick="navigateToEvent('prev')" 
                                            class="w-full bg-white/10 hover:bg-white/20 text-white py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Evento Anterior
                                    </button>
                                    <button onclick="navigateToEvent('next')" 
                                            class="w-full bg-white/10 hover:bg-white/20 text-white py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center">
                                        Próximo Evento
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        /**
         * Fechar modal de evento
         */
        function closeEventModal() {
            const modal = document.getElementById('event-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        /**
         * Navegar entre eventos no modal
         */
        function navigateToEvent(direction) {
            // Implementar navegação entre eventos
            console.log('Navegar:', direction);
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar filtros
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    filterEvents(this.dataset.category);
                });
            });
            
            // Fechar modal ao clicar fora
            document.getElementById('event-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEventModal();
                }
            });
            
            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEventModal();
                }
            });
            
            // Animações de scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observar eventos da timeline
            document.querySelectorAll('.timeline-event').forEach(event => {
                event.style.opacity = '0';
                event.style.transform = 'translateY(50px)';
                event.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                observer.observe(event);
            });
        });
    </script>
    @endpush
</x-layouts.employee>