<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificado - HCP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen">
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Verificação de Certificado
                </h1>
                <p class="text-gray-600">
                    Hemera Capital Partners - Sistema de Onboarding
                </p>
            </div>

            @if($certificate)
                <!-- Certificado Válido -->
                <div class="bg-white rounded-xl shadow-lg border-4 border-green-200 p-8">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-green-600 mb-2">
                            ✅ Certificado Válido
                        </h2>
                        <p class="text-gray-600">
                            Este certificado foi verificado e é autêntico
                        </p>
                    </div>

                    <!-- Informações do Certificado -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número do Certificado</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Código de Verificação</label>
                                <p class="text-lg font-mono font-semibold text-gray-900">{{ $certificate->verification_code }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Portador</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quiz</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->quiz->title }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data de Emissão</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->issued_at->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pontuação</label>
                                <p class="text-lg font-semibold text-green-600">{{ $certificate->attempt->score }}%</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $certificate->isValid() 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800' }}">
                                    {{ $certificate->status }}
                                </span>
                            </div>
                        </div>

                        @if($certificate->valid_until)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Válido até</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->valid_until->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Certificado Inválido -->
                <div class="bg-white rounded-xl shadow-lg border-4 border-red-200 p-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-red-600 mb-2">
                            ❌ Certificado Não Encontrado
                        </h2>
                        <p class="text-gray-600 mb-6">
                            {{ $message }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Verifique se o código de verificação está correto ou entre em contato conosco.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Rodapé -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} Hemera Capital Partners. Todos os direitos reservados.
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Sistema de verificação automática de certificados digitais
                </p>
            </div>
        </div>
    </div>
</body>
</html>