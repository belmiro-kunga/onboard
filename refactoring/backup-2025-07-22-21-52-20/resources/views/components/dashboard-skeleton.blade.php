{{-- Componente de Skeleton para Dashboard HCP --}}
@props(['type' => 'default'])

@if($type === 'default')
    <div class="space-y-6">
        <!-- Header Skeleton -->
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="h-8 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-64 animate-pulse"></div>
                <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-96 animate-pulse"></div>
            </div>
            <div class="w-32 h-10 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-lg animate-pulse"></div>
        </div>

        <!-- Stats Cards Skeleton -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @for($i = 0; $i < 4; $i++)
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-xl p-6 shadow-hcp">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-20 animate-pulse"></div>
                            <div class="h-8 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-16 animate-pulse"></div>
                        </div>
                        <div class="w-12 h-12 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-xl animate-pulse"></div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Content Grid Skeleton -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-xl p-6 shadow-hcp">
                    <div class="space-y-4">
                        <div class="h-6 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-48 animate-pulse"></div>
                        <div class="space-y-3">
                            @for($i = 0; $i < 5; $i++)
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full animate-pulse"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                                        <div class="h-3 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-2/3 animate-pulse"></div>
                                    </div>
                                    <div class="w-20 h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-xl p-6 shadow-hcp">
                    <div class="space-y-4">
                        <div class="h-6 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-32 animate-pulse"></div>
                        <div class="space-y-3">
                            @for($i = 0; $i < 3; $i++)
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full animate-pulse"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endif 