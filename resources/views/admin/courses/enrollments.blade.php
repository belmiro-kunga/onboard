<x-layouts.admin title="Matrículas do Curso">
    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Matrículas do Curso: {{ $course->title }}</h1>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Progresso</th>
                    <th>Data de Inscrição</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->user->name ?? '-' }}</td>
                        <td>{{ $enrollment->status_label ?? $enrollment->status }}</td>
                        <td>{{ $enrollment->progress_percentage ?? 0 }}%</td>
                        <td>{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-8">Nenhuma matrícula encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $enrollments->links() }}
        </div>
    </div>
</x-layouts.admin> 