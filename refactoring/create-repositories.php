<?php

/**
 * Script para criar repositories adicionais
 */

echo "📊 Criando repositories adicionais...\n";

$basePath = dirname(__DIR__);
$repositoryPath = $basePath . '/app/Repositories';

// QuizRepository
$quizRepositoryContent = '<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Models\QuizAttempt;

class QuizRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Quiz());
    }

    public function getActive()
    {
        return $this->model->where(\'is_active\', true)->orderBy(\'title\')->get();
    }

    public function getUserAttempts(int $userId, string $startDate = null)
    {
        $query = QuizAttempt::where(\'user_id\', $userId);
        
        if ($startDate) {
            $query->where(\'created_at\', \'>=\', $startDate);
        }
        
        return $query->get();
    }

    public function getWithQuestions(int $quizId)
    {
        return $this->model->with(\'questions\')->find($quizId);
    }

    public function countAttempts(int $quizId): int
    {
        return QuizAttempt::where(\'quiz_id\', $quizId)->count();
    }
}
';

file_put_contents($repositoryPath . '/QuizRepository.php', $quizRepositoryContent);
echo "  ✅ QuizRepository criado\n";

// SimuladoRepository
$simuladoRepositoryContent = '<?php

namespace App\Repositories;

use App\Models\Simulado;
use App\Models\SimuladoTentativa;

class SimuladoRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Simulado());
    }

    public function hasAttempts(int $simuladoId): bool
    {
        return SimuladoTentativa::where(\'simulado_id\', $simuladoId)->exists();
    }

    public function getUserAttempt(int $simuladoId, int $userId, int $tentativaId): ?SimuladoTentativa
    {
        return SimuladoTentativa::where(\'id\', $tentativaId)
                               ->where(\'user_id\', $userId)
                               ->where(\'simulado_id\', $simuladoId)
                               ->first();
    }

    public function getUserAttempts(int $simuladoId, int $userId)
    {
        return SimuladoTentativa::where(\'simulado_id\', $simuladoId)
                               ->where(\'user_id\', $userId)
                               ->orderBy(\'created_at\', \'desc\')
                               ->get();
    }

    public function countAttempts(int $simuladoId): int
    {
        return SimuladoTentativa::where(\'simulado_id\', $simuladoId)->count();
    }
}
';

file_put_contents($repositoryPath . '/SimuladoRepository.php', $simuladoRepositoryContent);
echo "  ✅ SimuladoRepository criado\n";

// ProgressRepository
$progressRepositoryContent = '<?php

namespace App\Repositories;

use App\Models\UserProgress;
use App\Models\UserGamification;

class ProgressRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new UserProgress());
    }

    public function getUserModuleProgress(int $userId, int $moduleId): ?UserProgress
    {
        return $this->model->where(\'user_id\', $userId)
                          ->where(\'module_id\', $moduleId)
                          ->first();
    }

    public function getUserGamification(int $userId): ?UserGamification
    {
        return UserGamification::where(\'user_id\', $userId)->first();
    }

    public function getUserProgress(int $userId)
    {
        return $this->model->where(\'user_id\', $userId)
                          ->with([\'module\', \'course\'])
                          ->orderBy(\'updated_at\', \'desc\')
                          ->get();
    }

    public function countCompletedModules(int $userId): int
    {
        return $this->model->where(\'user_id\', $userId)
                          ->where(\'status\', \'completed\')
                          ->count();
    }
}
';

file_put_contents($repositoryPath . '/ProgressRepository.php', $progressRepositoryContent);
echo "  ✅ ProgressRepository criado\n";

echo "✅ Repositories adicionais criados com sucesso!\n";