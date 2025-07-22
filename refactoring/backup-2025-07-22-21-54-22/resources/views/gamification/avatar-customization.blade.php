<x-layouts.app title="Customização de Avatar - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        🎨 Customização de Avatar
                    </h1>
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Personalize seu avatar e mostre sua personalidade
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Preview -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        Preview do Avatar
                    </h3>
                    
                    <div class="text-center">
                        <!-- Avatar Preview -->
                        <div id="avatar-preview" class="w-32 h-32 mx-auto mb-6 rounded-full flex items-center justify-center text-4xl font-bold text-white transition-all duration-300" 
                             style="background: linear-gradient(135deg, #4F46E5, #7C3AED);">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        
                        <!-- User Info -->
                        <div class="mb-6">
                            <h4 class="text-xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ auth()->user()->name }}
                            </h4>
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                {{ auth()->user()->getCurrentLevel() }} • {{ number_format(auth()->user()->getTotalPoints()) }} pontos
                            </p>
                        </div>

                        <!-- Save Button -->
                        <button id="save-avatar" class="w-full px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors font-medium">
                            💾 Salvar Avatar
                        </button>
                    </div>
                </div>

                <!-- Customization Options -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        Opções de Customização
                    </h3>

                    <!-- Background Colors -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-3">
                            Cor de Fundo
                        </h4>
                        <div class="grid grid-cols-6 gap-3" id="background-colors">
                            <!-- Colors will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Gradient Styles -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-3">
                            Estilo do Gradiente
                        </h4>
                        <div class="grid grid-cols-3 gap-3" id="gradient-styles">
                            <!-- Gradients will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Text Styles -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-3">
                            Estilo do Texto
                        </h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="text-style" value="bold" class="mr-2" checked>
                                <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Negrito</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="text-style" value="normal" class="mr-2">
                                <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Normal</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="text-style" value="italic" class="mr-2">
                                <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Itálico</span>
                            </label>
                        </div>
                    </div>

                    <!-- Border Styles -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-3">
                            Borda
                        </h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" id="border-enabled" class="mr-2">
                                <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Adicionar borda</span>
                            </label>
                            <div id="border-options" class="ml-6 space-y-2 hidden">
                                <div>
                                    <label class="block text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Espessura</label>
                                    <input type="range" id="border-width" min="1" max="8" value="3" class="w-full">
                                </div>
                                <div>
                                    <label class="block text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Cor da Borda</label>
                                    <div class="grid grid-cols-6 gap-2" id="border-colors">
                                        <!-- Border colors will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <button id="reset-avatar" class="w-full px-4 py-2 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-300 dark:hover:bg-hcp-secondary-600 transition-colors">
                        🔄 Resetar para Padrão
                    </button>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('gamification.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-300 dark:hover:bg-hcp-secondary-600 transition-colors">
                    ← Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="gamification" />

    @push('scripts')
    <script>
        class AvatarCustomizer {
            constructor() {
                this.preview = document.getElementById('avatar-preview');
                this.currentConfig = {
                    background: 'linear-gradient(135deg, #4F46E5, #7C3AED)',
                    textStyle: 'bold',
                    border: false,
                    borderWidth: 3,
                    borderColor: '#4F46E5'
                };
                
                this.init();
            }

            init() {
                this.setupColors();
                this.setupGradients();
                this.setupEventListeners();
                this.loadSavedConfig();
            }

            setupColors() {
                const colors = [
                    '#4F46E5', '#7C3AED', '#EC4899', '#EF4444',
                    '#F59E0B', '#10B981', '#06B6D4', '#8B5CF6',
                    '#F97316', '#84CC16', '#6366F1', '#14B8A6'
                ];

                const backgroundContainer = document.getElementById('background-colors');
                const borderContainer = document.getElementById('border-colors');

                colors.forEach(color => {
                    // Background colors
                    const bgButton = document.createElement('button');
                    bgButton.className = 'w-8 h-8 rounded-full border-2 border-hcp-secondary-300 dark:border-hcp-secondary-600 hover:scale-110 transition-transform';
                    bgButton.style.backgroundColor = color;
                    bgButton.onclick = () => this.setBackgroundColor(color);
                    backgroundContainer.appendChild(bgButton);

                    // Border colors
                    const borderButton = document.createElement('button');
                    borderButton.className = 'w-6 h-6 rounded-full border border-hcp-secondary-300 dark:border-hcp-secondary-600 hover:scale-110 transition-transform';
                    borderButton.style.backgroundColor = color;
                    borderButton.onclick = () => this.setBorderColor(color);
                    borderContainer.appendChild(borderButton);
                });
            }

            setupGradients() {
                const gradients = [
                    'linear-gradient(135deg, #4F46E5, #7C3AED)',
                    'linear-gradient(135deg, #EC4899, #EF4444)',
                    'linear-gradient(135deg, #F59E0B, #EF4444)',
                    'linear-gradient(135deg, #10B981, #06B6D4)',
                    'linear-gradient(135deg, #8B5CF6, #EC4899)',
                    'linear-gradient(135deg, #F97316, #F59E0B)'
                ];

                const container = document.getElementById('gradient-styles');

                gradients.forEach(gradient => {
                    const button = document.createElement('button');
                    button.className = 'w-12 h-12 rounded-full border-2 border-hcp-secondary-300 dark:border-hcp-secondary-600 hover:scale-110 transition-transform';
                    button.style.background = gradient;
                    button.onclick = () => this.setBackground(gradient);
                    container.appendChild(button);
                });
            }

            setupEventListeners() {
                // Text style
                document.querySelectorAll('input[name="text-style"]').forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        this.setTextStyle(e.target.value);
                    });
                });

                // Border
                document.getElementById('border-enabled').addEventListener('change', (e) => {
                    this.toggleBorder(e.target.checked);
                });

                document.getElementById('border-width').addEventListener('input', (e) => {
                    this.setBorderWidth(e.target.value);
                });

                // Buttons
                document.getElementById('save-avatar').addEventListener('click', () => {
                    this.saveConfig();
                });

                document.getElementById('reset-avatar').addEventListener('click', () => {
                    this.resetConfig();
                });
            }

            setBackground(background) {
                this.currentConfig.background = background;
                this.updatePreview();
            }

            setBackgroundColor(color) {
                this.currentConfig.background = color;
                this.updatePreview();
            }

            setTextStyle(style) {
                this.currentConfig.textStyle = style;
                this.updatePreview();
            }

            toggleBorder(enabled) {
                this.currentConfig.border = enabled;
                document.getElementById('border-options').classList.toggle('hidden', !enabled);
                this.updatePreview();
            }

            setBorderWidth(width) {
                this.currentConfig.borderWidth = width;
                this.updatePreview();
            }

            setBorderColor(color) {
                this.currentConfig.borderColor = color;
                this.updatePreview();
            }

            updatePreview() {
                const preview = this.preview;
                
                // Background
                preview.style.background = this.currentConfig.background;
                
                // Text style
                switch(this.currentConfig.textStyle) {
                    case 'bold':
                        preview.style.fontWeight = 'bold';
                        preview.style.fontStyle = 'normal';
                        break;
                    case 'normal':
                        preview.style.fontWeight = 'normal';
                        preview.style.fontStyle = 'normal';
                        break;
                    case 'italic':
                        preview.style.fontWeight = 'normal';
                        preview.style.fontStyle = 'italic';
                        break;
                }
                
                // Border
                if (this.currentConfig.border) {
                    preview.style.border = `${this.currentConfig.borderWidth}px solid ${this.currentConfig.borderColor}`;
                } else {
                    preview.style.border = 'none';
                }

                // Animation
                preview.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    preview.style.transform = 'scale(1)';
                }, 200);
            }

            saveConfig() {
                fetch('/api/user/avatar-config', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.currentConfig)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.showToast('Avatar salvo com sucesso! 🎨', 'success');
                        
                        // Trigger confetti
                        if (window.gamificationAnimations) {
                            window.gamificationAnimations.createConfetti('#4F46E5');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showToast('Erro ao salvar avatar', 'error');
                });
            }

            loadSavedConfig() {
                fetch('/api/user/avatar-config')
                    .then(response => response.json())
                    .then(data => {
                        if (data.config) {
                            this.currentConfig = { ...this.currentConfig, ...data.config };
                            this.applyConfig();
                        }
                    })
                    .catch(error => console.error('Error loading config:', error));
            }

            applyConfig() {
                // Apply text style
                document.querySelector(`input[name="text-style"][value="${this.currentConfig.textStyle}"]`).checked = true;
                
                // Apply border
                document.getElementById('border-enabled').checked = this.currentConfig.border;
                document.getElementById('border-options').classList.toggle('hidden', !this.currentConfig.border);
                document.getElementById('border-width').value = this.currentConfig.borderWidth;
                
                this.updatePreview();
            }

            resetConfig() {
                this.currentConfig = {
                    background: 'linear-gradient(135deg, #4F46E5, #7C3AED)',
                    textStyle: 'bold',
                    border: false,
                    borderWidth: 3,
                    borderColor: '#4F46E5'
                };
                
                this.applyConfig();
                this.showToast('Avatar resetado para o padrão', 'info');
            }

            showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
                toast.textContent = message;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            new AvatarCustomizer();
        });
    </script>
    @endpush
</x-layouts.app>