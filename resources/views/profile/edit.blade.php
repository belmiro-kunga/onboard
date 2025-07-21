<x-layouts.app>
    <div class="min-h-screen bg-hcp-secondary-50 dark:bg-hcp-secondary-900">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            ✏️ Editar Perfil
                        </h1>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mt-2">
                            Atualize suas informações pessoais
                        </p>
                    </div>
                    <a href="{{ route('profile.index') }}" 
                       class="px-4 py-2 bg-hcp-secondary-500 text-white rounded-lg hover:bg-hcp-secondary-600 transition-colors">
                        ← Voltar ao Perfil
                    </a>
                </div>
            </div>

            <!-- Alertas -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário -->
            <div class="max-w-2xl mx-auto">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Nome -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Nome Completo *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white"
                                   required>
                        </div>

                        <!-- E-mail -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                E-mail *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white"
                                   required>
                        </div>

                        <!-- Telefone -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Telefone
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="(11) 99999-9999"
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white">
                        </div>

                        <!-- Data de Nascimento -->
                        <div class="mb-6">
                            <label for="birthdate" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Data de Nascimento
                            </label>
                            <input type="date" 
                                   id="birthdate" 
                                   name="birthdate" 
                                   value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white">
                        </div>

                        <!-- Bio -->
                        <div class="mb-6">
                            <label for="bio" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Sobre Mim
                            </label>
                            <textarea id="bio" 
                                      name="bio" 
                                      rows="4"
                                      placeholder="Conte um pouco sobre você..."
                                      class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white resize-none">{{ old('bio', $user->bio) }}</textarea>
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                Máximo 500 caracteres
                            </p>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-between pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <a href="{{ route('profile.index') }}" 
                               class="px-6 py-2 bg-hcp-secondary-500 text-white rounded-lg hover:bg-hcp-secondary-600 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                                💾 Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="profile" />
</x-layouts.app> 