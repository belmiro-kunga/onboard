<x-layouts.app>
    <div class="min-h-screen bg-hcp-secondary-50 dark:bg-hcp-secondary-900">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                    👤 Meu Perfil
                </h1>
                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mt-2">
                    Gerencie suas informações pessoais e configurações da conta
                </p>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informações do Perfil -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Dados Pessoais -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                                📋 Dados Pessoais
                            </h2>
                            <a href="{{ route('profile.edit') }}" 
                               class="px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors text-sm">
                                ✏️ Editar
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Nome Completo
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    E-mail
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->email }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Telefone
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->phone ?: 'Não informado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Data de Nascimento
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->birthdate ? $user->birthdate->format('d/m/Y') : 'Não informada' }}
                                </p>
                            </div>

                            @if($user->bio)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                        Sobre Mim
                                    </label>
                                    <p class="text-hcp-secondary-900 dark:text-white">
                                        {{ $user->bio }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações da Conta -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                                🔐 Segurança da Conta
                            </h2>
                            <a href="{{ route('profile.change-password') }}" 
                               class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-sm">
                                🔑 Alterar Senha
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Departamento
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->department ?: 'Não definido' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Cargo
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->position ?: 'Não definido' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Data de Contratação
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->hire_date ? $user->hire_date->format('d/m/Y') : 'Não informada' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                    Último Login
                                </label>
                                <p class="text-hcp-secondary-900 dark:text-white font-medium">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Avatar -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            🖼️ Foto de Perfil
                        </h3>
                        
                        <div class="text-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-hcp-500">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" 
                                     alt="{{ $user->name }}" 
                                     class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-hcp-500">
                            @endif
                            
                            <button class="px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors text-sm">
                                📷 Alterar Foto
                            </button>
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            ⚡ Ações Rápidas
                        </h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('profile.edit') }}" 
                               class="block w-full px-4 py-2 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                ✏️ Editar Dados
                            </a>
                            
                            <a href="{{ route('profile.change-password') }}" 
                               class="block w-full px-4 py-2 bg-orange-500 text-white text-center rounded-lg hover:bg-orange-600 transition-colors text-sm">
                                🔑 Alterar Senha
                            </a>
                            
                            <a href="{{ route('certificates.index') }}" 
                               class="block w-full px-4 py-2 bg-green-500 text-white text-center rounded-lg hover:bg-green-600 transition-colors text-sm">
                                🎓 Meus Certificados
                            </a>
                            
                            <a href="{{ route('progress.index') }}" 
                               class="block w-full px-4 py-2 bg-purple-500 text-white text-center rounded-lg hover:bg-purple-600 transition-colors text-sm">
                                📊 Meu Progresso
                            </a>
                        </div>
                    </div>

                    <!-- Status da Conta -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            📊 Status da Conta
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Status</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Função</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">2FA</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->two_factor_enabled ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                    {{ $user->two_factor_enabled ? 'Ativado' : 'Desativado' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="profile" />
</x-layouts.app> 