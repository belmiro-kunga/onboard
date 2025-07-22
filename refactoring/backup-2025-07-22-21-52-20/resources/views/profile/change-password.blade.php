<x-layouts.app>
    <div class="min-h-screen bg-hcp-secondary-50 dark:bg-hcp-secondary-900">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            🔑 Alterar Senha
                        </h1>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mt-2">
                            Atualize sua senha para manter sua conta segura
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
            <div class="max-w-md mx-auto">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('PUT')

                        <!-- Senha Atual -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Senha Atual *
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white"
                                   required>
                        </div>

                        <!-- Nova Senha -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Nova Senha *
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white"
                                   required>
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                Mínimo 8 caracteres
                            </p>
                        </div>

                        <!-- Confirmar Nova Senha -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                Confirmar Nova Senha *
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-transparent dark:bg-hcp-secondary-700 dark:text-white"
                                   required>
                        </div>

                        <!-- Dicas de Segurança -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                                💡 Dicas para uma senha segura:
                            </h4>
                            <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                <li>• Use pelo menos 8 caracteres</li>
                                <li>• Combine letras maiúsculas e minúsculas</li>
                                <li>• Inclua números e símbolos</li>
                                <li>• Evite informações pessoais óbvias</li>
                                <li>• Não use a mesma senha em outros sites</li>
                            </ul>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-between pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <a href="{{ route('profile.index') }}" 
                               class="px-6 py-2 bg-hcp-secondary-500 text-white rounded-lg hover:bg-hcp-secondary-600 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                                🔒 Alterar Senha
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