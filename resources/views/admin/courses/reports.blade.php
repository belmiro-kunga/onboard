<x-layouts.admin title="Relatórios do Curso">
    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Relatórios do Curso: {{ $course->title }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total de Matrículas</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_enrollments'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Concluídos</div>
                <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Em Progresso</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Inscritos</div>
                <div class="text-2xl font-bold text-blue-600">{{ $stats['enrolled'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Abandonos</div>
                <div class="text-2xl font-bold text-red-600">{{ $stats['dropped'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Taxa de Conclusão</div>
                <div class="text-2xl font-bold text-purple-600">{{ $stats['completion_rate'] ?? 0 }}%</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Tempo Médio de Conclusão</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['avg_completion_days'] ?? 0 }} dias</div>
            </div>
        </div>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 mt-8">Progresso por Departamento</h2>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th>Departamento</th>
                    <th>Total</th>
                    <th>Concluídos</th>
                    <th>Taxa de Conclusão</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departmentStats as $dept)
                    <tr>
                        <td>{{ $dept->department }}</td>
                        <td>{{ $dept->total }}</td>
                        <td>{{ $dept->completed }}</td>
                        <td>{{ $dept->completion_rate }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-8">Nenhum dado de departamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin> 