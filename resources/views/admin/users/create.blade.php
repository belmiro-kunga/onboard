@extends('layouts.admin-modern')

@section('title', 'Criar Novo Usuário')

@section('header')
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        {{ __('Criar Novo Usuário') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nome Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500"
                           required autofocus>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- E-mail -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        E-mail <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Senha <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Senha -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Confirmar Senha <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500"
                           required>
                </div>

                <!-- Cargo -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Cargo
                    </label>
                    <input type="text" name="position" id="position" value="{{ old('position') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500">
                    @error('position')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Departamento -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Departamento
                    </label>
                    <input type="text" name="department" id="department" value="{{ old('department') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500">
                    @error('department')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Função (Role) -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Função <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-hcp-500 focus:ring-hcp-500"
                            required>
                        <option value="">Selecione uma função</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Gerente</option>
                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Funcionário</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" 
                                   class="h-4 w-4 text-hcp-600 focus:ring-hcp-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Usuário ativo
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Foto de Perfil -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Foto de Perfil
                    </label>
                    <div class="mt-1 flex items-center">
                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <svg class="h-full w-full text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                        <label for="profile_photo" class="ml-5">
                            <span class="px-3 py-1.5 text-sm leading-4 font-medium text-hcp-700 dark:text-hcp-300 bg-hcp-100 dark:bg-hcp-900/30 rounded-md hover:bg-hcp-200 dark:hover:bg-hcp-800/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500 cursor-pointer">
                                Alterar
                            </span>
                            <input id="profile_photo" name="profile_photo" type="file" class="sr-only">
                        </label>
                    </div>
                    @error('profile_photo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-hcp-600 hover:bg-hcp-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500">
                    Salvar Usuário
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Preview da imagem de perfil
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('profile_photo');
        const imagePreview = document.querySelector('.inline-block.h-12.w-12.rounded-full.overflow-hidden svg');
        const imageContainer = document.querySelector('.inline-block.h-12.w-12.rounded-full.overflow-hidden');
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Remove o SVG se existir
                    if (imagePreview) {
                        imagePreview.remove();
                    }
                    
                    // Cria uma nova imagem
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = 'Preview';
                    img.className = 'h-full w-full object-cover';
                    
                    // Remove a imagem anterior se existir
                    const existingImg = imageContainer.querySelector('img');
                    if (existingImg) {
                        existingImg.remove();
                    }
                    
                    // Adiciona a nova imagem
                    imageContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
