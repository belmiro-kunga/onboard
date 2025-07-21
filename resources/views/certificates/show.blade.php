<x-layouts.app title="Certificado {{ $certificate->certificate_number }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900 dark:to-emerald-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                    Certificado Digital
                </h1>
                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    Certificado #{{ $certificate->certificate_number }}
                </p>
            </div>

            <!-- Certificado -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-lg border-4 border-green-200 dark:border-green-800 p-8 mb-8">
                <!-- Logo e Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                        Hemera Capital Partners
                    </h2>
                    <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Sistema de Onboarding Interativo
                    </p>
                </div>

                <!-- Título do Certificado -->
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold text-green-600 dark:text-green-400 mb-4">
                        CERTIFICADO DE CONCLUSÃO
                    </h3>
                    <div class="w-32 h-1 bg-gradient-to-r from-green-400 to-green-600 mx-auto"></div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="text-center mb-8">
                    <p class="text-lg text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-4">
                        Certificamos que
                    </p>
                    <h4 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white mb-4">
                        {{ $certificate->user->name }}
                    </h4>
                    <p class="text-lg text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        concluiu com êxito o quiz
                    </p>
                    <h5 class="text-xl font-semibold text-hcp-primary-600 dark:text-hcp-primary-400 mb-4">
                        "{{ $certificate->quiz->title }}"
                    </h5>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        obtendo <strong class="text-green-600">{{ $certificate->attempt->score }}%</strong> de aproveitamento
                        em <strong>{{ $certificate->attempt->formatted_time_spent }}</strong>,
                        superando a pontuação mínima de {{ $certificate->quiz->passing_score }}%.
                    </p>
                </div>

                <!-- Informações Adicionais -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Data de Emissão</p>
                        <p class="font-semibold text-hcp-secondary-900 dark:text-white">
                            {{ $certificate->issued_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Categoria</p>
                        <p class="font-semibold text-hcp-secondary-900 dark:text-white">
                            {{ $certificate->quiz->formatted_category }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Nível</p>
                        <p class="font-semibold text-hcp-secondary-900 dark:text-white">
                            {{ $certificate->quiz->formatted_difficulty }}
                        </p>
                    </div>
                </div>

                <!-- Código de Verificação -->
                <div class="text-center mb-8">
                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                        Código de Verificação
                    </p>
                    <p class="font-mono text-lg font-bold text-hcp-secondary-900 dark:text-white bg-hcp-secondary-100 dark:bg-hcp-secondary-700 px-4 py-2 rounded-lg inline-block">
                        {{ $certificate->verification_code }}
                    </p>
                </div>

                <!-- Status -->
                <div class="text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        {{ $certificate->isValid() 
                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                        {{ $certificate->status }}
                        @if($certificate->valid_until)
                            - Válido até {{ $certificate->valid_until->format('d/m/Y') }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('certificates.download', $certificate) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
                
                <a href="{{ route('certificates.verify', $certificate->verification_code) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Verificar Autenticidade
                </a>
                
                <a href="{{ route('certificates.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Voltar aos Certificados
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>