<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Menu Colapsável</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen" 
         x-data="{ sidebarCollapsed: false }"
         x-init="console.log('Alpine.js funcionando!', $data)">
        
        <!-- Sidebar de Teste -->
        <div class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg transform transition-all duration-300 ease-in-out" 
             :class="{
                 'w-64': !sidebarCollapsed,
                 'w-16': sidebarCollapsed
             }">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">HCP</span>
                    </div>
                    <span class="text-xl font-semibold text-gray-900 transition-all duration-300"
                          :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Sistema HCP
                    </span>
                </div>
            </div>

            <!-- Menu de Teste -->
            <nav class="mt-6 px-3">
                <div class="space-y-2">
                    <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 relative">
                        <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                            Dashboard
                        </span>
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" 
                             class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                            Dashboard
                        </div>
                    </a>

                    <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 relative">
                        <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                            Módulos
                        </span>
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" 
                             class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                            Módulos
                        </div>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Conteúdo Principal -->
        <div class="transition-all duration-300 ease-in-out" 
             :class="{
                 'lg:pl-64': !sidebarCollapsed,
                 'lg:pl-16': sidebarCollapsed
             }">
            
            <!-- Barra Superior -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Botão Toggle -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed" 
                            class="p-2 text-gray-600 hover:text-blue-600 transition-colors rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-900">Teste Menu Colapsável</h1>

                    <div class="text-sm text-gray-600">
                        Estado: <span x-text="sidebarCollapsed ? 'Colapsado' : 'Expandido'"></span>
                    </div>
                </div>
            </div>

            <!-- Conteúdo da Página -->
            <main class="p-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Teste do Menu Colapsável</h2>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-medium text-blue-900">Instruções:</h3>
                            <ul class="mt-2 text-blue-800 space-y-1">
                                <li>• Clique no botão de seta na barra superior</li>
                                <li>• O menu deve colapsar/expandir suavemente</li>
                                <li>• Passe o mouse sobre os ícones quando colapsado</li>
                                <li>• Verifique se os tooltips aparecem</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg">
                            <h3 class="font-medium text-green-900">Status:</h3>
                            <p class="mt-2 text-green-800">
                                Menu está: <strong x-text="sidebarCollapsed ? 'COLAPSADO (64px)' : 'EXPANDIDO (256px)'"></strong>
                            </p>
                        </div>

                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <h3 class="font-medium text-yellow-900">Debug:</h3>
                            <p class="mt-2 text-yellow-800">
                                Alpine.js carregado: <span x-text="typeof Alpine !== 'undefined' ? 'SIM' : 'NÃO'"></span>
                            </p>
                            <p class="text-yellow-800">
                                Estado atual: <span x-text="JSON.stringify($data)"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Debug adicional
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado');
            console.log('Alpine disponível:', typeof Alpine !== 'undefined');
            if (typeof Alpine !== 'undefined') {
                console.log('Alpine versão:', Alpine.version);
            }
        });
    </script>
</body>
</html> 