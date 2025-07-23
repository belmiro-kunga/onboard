<x-layouts.admin title="Criar Curso">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.courses.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white p-3 rounded-xl transition-all duration-300 hover:scale-105">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Criar Novo Curso</h1>
                            <p class="text-purple-100 mt-1">Configure um novo curso de aprendizado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form id="course-wizard-form" action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <!-- Wizard Steps -->
                <div class="flex justify-between items-center mb-8">
                    <div class="flex-1 flex items-center">
                        <div :class="step === 1 ? 'bg-purple-600 text-white' : 'bg-slate-700 text-slate-300'" class="w-8 h-8 flex items-center justify-center rounded-full font-bold transition-all duration-300">1</div>
                        <span class="ml-2 mr-4 text-sm font-medium" :class="step === 1 ? 'text-white' : 'text-slate-400'">Informações</span>
                        <div class="flex-1 h-0.5 bg-slate-600"></div>
                    </div>
                    <div class="flex-1 flex items-center">
                        <div :class="step === 2 ? 'bg-purple-600 text-white' : 'bg-slate-700 text-slate-300'" class="w-8 h-8 flex items-center justify-center rounded-full font-bold transition-all duration-300">2</div>
                        <span class="ml-2 mr-4 text-sm font-medium" :class="step === 2 ? 'text-white' : 'text-slate-400'">Imagem & Configurações</span>
                        <div class="flex-1 h-0.5 bg-slate-600"></div>
                    </div>
                    <div class="flex-1 flex items-center">
                        <div :class="step === 3 ? 'bg-purple-600 text-white' : 'bg-slate-700 text-slate-300'" class="w-8 h-8 flex items-center justify-center rounded-full font-bold transition-all duration-300">3</div>
                        <span class="ml-2 text-sm font-medium" :class="step === 3 ? 'text-white' : 'text-slate-400'">Confirmação</span>
                    </div>
                </div>

                <!-- Passo 1: Informações Básicas -->
                <div x-show="step === 1">
                    @include('admin.courses.partials.wizard-step1')
                </div>
                <!-- Passo 2: Imagem e Configurações -->
                <div x-show="step === 2">
                    @include('admin.courses.partials.wizard-step2')
                </div>
                <!-- Passo 3: Confirmação -->
                <div x-show="step === 3">
                    @include('admin.courses.partials.wizard-step3')
                </div>

                <!-- Navegação do Wizard -->
                <div class="flex justify-between items-center pt-6">
                    <button type="button" @click="step = Math.max(1, step - 1)" :disabled="step === 1"
                        class="inline-flex items-center px-6 py-3 border border-slate-600 text-slate-300 bg-slate-800/50 hover:bg-slate-700/50 rounded-xl font-medium transition-all duration-300 disabled:opacity-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Voltar
                    </button>
                    <button type="button" @click="step = Math.min(3, step + 1)" x-show="step < 3"
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-purple-500/25">
                        Próximo
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                    <button type="submit" x-show="step === 3"
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-purple-500/25">
                        Criar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('wizard', () => ({
                step: 1
            }));
        });
    </script>
</x-layouts.admin>