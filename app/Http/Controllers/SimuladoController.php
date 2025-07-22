<?php

namespace App\Http\Controllers;


use App\Repositories\SimuladoRepository;use App\Models\Simulado;
use App\Models\SimuladoTentativa;
use App\Models\SimuladoQuestao;
use App\Models\SimuladoResposta;
use App\Models\User;
use App\Contracts\GamificationServiceInterface;
use App\Contracts\CertificateServiceInterface;
use App\Contracts\NotificationServiceInterface;
use App\Events\SimuladoCompleted;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimuladoController extends Controller
{
    protected $gamificationService;
    protected $certificateService;
    protected $notificationService;

    public function __construct(
        GamificationServiceInterface $gamificationService, 
        CertificateServiceInterface $certificateService,
        NotificationServiceInterface $notificationService
    ) {
        $this->gamificationService = $gamificationService;
        $this->certificateService = $certificateService;
        $this->notificationService = $notificationService;
    }

    /**
     * Exibir lista de simulados
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $simulados = Simulado::available()
            ->with(['tentativas' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Estatísticas do usuário
        $stats = $this->getUserStats($user);

        return view('simulados.index', compact('simulados', 'stats'));
    }

    /**
     * Exibir detalhes do simulado
     */
    public function show($id): View
    {
        $user = Auth::user();
        $simulado = Simulado::with(['questoesAtivas', 'tentativas' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->findOrFail((int) $id);

        if (!$simulado->isAvailable()) {
            abort(404, 'Simulado não está disponível.');
        }

        // Verificar se pode fazer o simulado
        $canTake = $simulado->canUserTake($user);
        $bestAttempt = $simulado->getBestAttempt($user);
        $hasPassed = $simulado->hasUserPassed($user);

        return view('simulados.show', compact('simulado', 'canTake', 'bestAttempt', 'hasPassed'));
    }

    /**
     * Iniciar simulado
     */
    public function start($id): JsonResponse
    {
        $user = Auth::user();
        $simulado = Simulado::findOrFail((int) $id);

        if (!$simulado->canUserTake($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Você não pode iniciar este simulado.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Criar nova tentativa
            $tentativa = SimuladoTentativa::create([
                'user_id' => $user->id,
                'simulado_id' => $simulado->id,
                'status' => 'in_progress',
                'iniciado_em' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Simulado iniciado com sucesso!',
                'redirect_url' => route('simulados.attempt', ['id' => $simulado->id, 'tentativa' => $tentativa->id])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao iniciar simulado', [
                'user_id' => $user->id,
                'simulado_id' => $simulado->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao iniciar simulado.'
            ], 500);
        }
    }

    /**
     * Exibir página de tentativa
     */
    public function attempt($id, $tentativaId): View
    {
        $user = Auth::user();
        $simulado = Simulado::with('questoesAtivas')->findOrFail((int) $id);
        $tentativa = SimuladoTentativa::where('id', (int) $tentativaId)
            ->where('user_id', $user->id)
            ->where('simulado_id', (int) $id)
            ->firstOrFail();

        if ($tentativa->status === 'completed') {
            return redirect()->route('simulados.result', ['id' => $simulado->id, 'tentativa' => $tentativa->id]);
        }

        $questoes = $simulado->questoesAtivas;
        $respostas = $tentativa->respostas->keyBy('questao_id');

        return view('simulados.attempt', compact('simulado', 'tentativa', 'questoes', 'respostas'));
    }

    /**
     * Salvar resposta
     */
    public function saveAnswer(Request $request, $id, $tentativaId): JsonResponse
    {
        $user = Auth::user();
        $simulado = Simulado::findOrFail((int) $id);
        $tentativa = SimuladoTentativa::where('id', (int) $tentativaId)
            ->where('user_id', $user->id)
            ->where('simulado_id', (int) $id)
            ->firstOrFail();

        $request->validate([
            'questao_id' => 'required|integer|exists:simulado_questoes,id',
            'resposta' => 'required|string',
            'tempo_questao' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Verificar se a questão pertence ao simulado
            $questao = SimuladoQuestao::where('id', $request->questao_id)
                ->where('simulado_id', $simulado->id)
                ->firstOrFail();

            // Verificar se a resposta está correta
            $correta = $request->resposta === $questao->resposta_correta;

            // Salvar ou atualizar resposta
            SimuladoResposta::updateOrCreate(
                [
                    'tentativa_id' => $tentativa->id,
                    'questao_id' => $questao->id
                ],
                [
                    'resposta' => $request->resposta,
                    'correta' => $correta,
                    'tempo_questao' => $request->tempo_questao ?? 0,
                    'respondida_em' => Carbon::now()
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resposta salva com sucesso!',
                'correta' => $correta
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar resposta', [
                'user_id' => $user->id,
                'tentativa_id' => $tentativa->id,
                'questao_id' => $request->questao_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar resposta.'
            ], 500);
        }
    }

    /**
     * Finalizar simulado
     */
    public function finish(Request $request, $id, $tentativaId): JsonResponse
    {
        $user = Auth::user();
        $simulado = Simulado::findOrFail((int) $id);
        $tentativa = SimuladoTentativa::where('id', (int) $tentativaId)
            ->where('user_id', $user->id)
            ->where('simulado_id', (int) $id)
            ->firstOrFail();

        if ($tentativa->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Simulado já foi finalizado.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calcular score
            $respostas = $tentativa->respostas;
            $totalQuestoes = $simulado->questoes_count;
            $respostasCorretas = $respostas->where('correta', true)->count();
            $score = $totalQuestoes > 0 ? round(($respostasCorretas / $totalQuestoes) * 100) : 0;

            // Calcular tempo gasto
            $tempoGasto = $tentativa->iniciado_em ? $tentativa->iniciado_em->diffInSeconds(Carbon::now()) : 0;

            // Atualizar tentativa
            $tentativa->update([
                'status' => 'completed',
                'score' => $score,
                'tempo_gasto' => $tempoGasto,
                'finalizado_em' => Carbon::now()
            ]);

            // Verificar se passou
            $hasPassed = $score >= $simulado->passing_score;
            
            // Calcular pontos de gamificação
            $pontosBase = 0;
            $pontosBonus = 0;
            $motivoBonus = [];

            if ($hasPassed) {
                // Pontos base por aprovação
                $pontosBase = $simulado->pontos_recompensa;
                
                // Bônus por performance (score alto)
                if ($score >= 90) {
                    $pontosBonus += 50;
                    $motivoBonus[] = 'Excelente performance (+50 pontos)';
                } elseif ($score >= 80) {
                    $pontosBonus += 25;
                    $motivoBonus[] = 'Boa performance (+25 pontos)';
                }
                
                // Bônus por tempo (se completou rápido)
                $tempoMedio = $simulado->tempo_estimado * 60; // converter para segundos
                if ($tempoGasto <= $tempoMedio * 0.7) {
                    $pontosBonus += 30;
                    $motivoBonus[] = 'Completou rapidamente (+30 pontos)';
                } elseif ($tempoGasto <= $tempoMedio * 0.85) {
                    $pontosBonus += 15;
                    $motivoBonus[] = 'Tempo bom (+15 pontos)';
                }
                
                // Bônus por primeira tentativa
                $tentativasAnteriores = SimuladoTentativa::where('user_id', $user->id)
                    ->where('simulado_id', $simulado->id)
                    ->where('id', '!=', $tentativa->id)
                    ->count();
                
                if ($tentativasAnteriores === 0) {
                    $pontosBonus += 20;
                    $motivoBonus[] = 'Primeira tentativa (+20 pontos)';
                }
                
                // Bônus por acertos consecutivos
                $acertosConsecutivos = 0;
                $maxAcertosConsecutivos = 0;
                foreach ($respostas as $resposta) {
                    if ($resposta->correta) {
                        $acertosConsecutivos++;
                        $maxAcertosConsecutivos = max($maxAcertosConsecutivos, $acertosConsecutivos);
                    } else {
                        $acertosConsecutivos = 0;
                    }
                }
                
                if ($maxAcertosConsecutivos >= 5) {
                    $pontosBonus += 25;
                    $motivoBonus[] = 'Sequência de acertos (+25 pontos)';
                }
                
                // Adicionar pontos totais
                $pontosTotais = $pontosBase + $pontosBonus;
                $this->gamificationService->addPoints($user, $pontosTotais, 'simulado_aprovado', [
                    'simulado_id' => $simulado->id,
                    'tentativa_id' => $tentativa->id,
                    'score' => $score,
                    'pontos_base' => $pontosBase,
                    'pontos_bonus' => $pontosBonus,
                    'motivos_bonus' => $motivoBonus
                ]);

                // Gerar certificado
                $this->certificateService->generateSimuladoCertificate($user, $simulado, $tentativa);
                
                // Enviar notificação de sucesso
                $this->notificationService->sendToUser(
                    $user,
                    '🎉 Simulado Aprovado!',
                    "Parabéns! Você aprovou o simulado '{$simulado->titulo}' com {$score}% de acerto e ganhou {$pontosTotais} pontos!",
                    'success',
                    'badge-check',
                    'green',
                    route('simulados.result', ['id' => $simulado->id, 'tentativa' => $tentativa->id]),
                    [
                        'simulado_id' => $simulado->id,
                        'score' => $score,
                        'pontos_ganhos' => $pontosTotais
                    ]
                );
                
            } else {
                // Pontos de participação (mesmo reprovando)
                $pontosParticipacao = max(5, round($score / 10));
                $this->gamificationService->addPoints($user, $pontosParticipacao, 'simulado_participacao', [
                    'simulado_id' => $simulado->id,
                    'tentativa_id' => $tentativa->id,
                    'score' => $score
                ]);
                
                // Enviar notificação de incentivo
                $this->notificationService->sendToUser(
                    $user,
                    '📚 Continue Estudando!',
                    "Você completou o simulado '{$simulado->titulo}' com {$score}%. Não desista, você pode tentar novamente!",
                    'info',
                    'book-open',
                    'blue',
                    route('simulados.result', ['id' => $simulado->id, 'tentativa' => $tentativa->id]),
                    [
                        'simulado_id' => $simulado->id,
                        'score' => $score
                    ]
                );
            }

            // Disparar evento
            event(new SimuladoCompleted($user, $simulado, $tentativa, $hasPassed));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $hasPassed ? 'Parabéns! Simulado aprovado!' : 'Simulado finalizado. Continue estudando!',
                'score' => $score,
                'has_passed' => $hasPassed,
                'pontos_ganhos' => $hasPassed ? ($pontosBase + $pontosBonus) : $pontosParticipacao,
                'pontos_base' => $hasPassed ? $pontosBase : 0,
                'pontos_bonus' => $hasPassed ? $pontosBonus : 0,
                'motivos_bonus' => $hasPassed ? $motivoBonus : [],
                'redirect_url' => route('simulados.result', ['id' => $simulado->id, 'tentativa' => $tentativa->id])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao finalizar simulado', [
                'user_id' => $user->id,
                'tentativa_id' => $tentativaId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar simulado.'
            ], 500);
        }
    }

    /**
     * Exibir resultado do simulado
     */
    public function result($id, $tentativaId): View
    {
        $user = Auth::user();
        $simulado = Simulado::findOrFail((int) $id);
        $tentativa = SimuladoTentativa::where('id', (int) $tentativaId)
            ->where('user_id', $user->id)
            ->where('simulado_id', (int) $id)
            ->with(['respostas.questao'])
            ->firstOrFail();

        if ($tentativa->status !== 'completed') {
            return redirect()->route('simulados.attempt', ['id' => $id, 'tentativa' => $tentativaId]);
        }

        $stats = $tentativa->getStats();
        $hasPassed = $tentativa->isPassed();

        return view('simulados.result', compact('simulado', 'tentativa', 'stats', 'hasPassed'));
    }

    /**
     * Exibir relatório detalhado
     */
    public function report($id, $tentativaId): View
    {
        $user = Auth::user();
        $simulado = Simulado::with('questoesAtivas')->findOrFail((int) $id);
        $tentativa = SimuladoTentativa::where('id', (int) $tentativaId)
            ->where('user_id', $user->id)
            ->where('simulado_id', (int) $id)
            ->with(['respostas.questao'])
            ->firstOrFail();

        $respostas = $tentativa->respostas;
        $questoes = $simulado->questoesAtivas;

        // Mapear respostas por questão
        $respostasPorQuestao = $respostas->keyBy('questao_id');

        return view('simulados.report', compact('simulado', 'tentativa', 'questoes', 'respostasPorQuestao'));
    }

    /**
     * Exibir histórico de tentativas
     */
    public function history(): View
    {
        $user = Auth::user();
        
        $tentativas = SimuladoTentativa::where('user_id', $user->id)
            ->with(['simulado'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = $this->getUserStats($user);

        return view('simulados.history', compact('tentativas', 'stats'));
    }

    /**
     * Obter estatísticas do usuário
     */
    private function getUserStats(User $user): array
    {
        $tentativas = SimuladoTentativa::where('user_id', $user->id);
        $tentativasCompletadas = SimuladoTentativa::where('user_id', $user->id)->where('simulado_tentativas.status', 'completed');
        
        // Tentativas aprovadas
        $tentativasAprovadas = SimuladoTentativa::where('user_id', $user->id)
            ->where('simulado_tentativas.status', 'completed')
            ->whereHas('simulado', function($query) {
                $query->whereRaw('simulado_tentativas.score >= simulados.passing_score');
            });

        // Pontos ganhos
        $pontosGanhos = SimuladoTentativa::where('user_id', $user->id)
            ->where('simulado_tentativas.status', 'completed')
            ->join('simulados', 'simulado_tentativas.simulado_id', '=', 'simulados.id')
            ->whereRaw('simulado_tentativas.score >= simulados.passing_score')
            ->sum('simulados.pontos_recompensa');

        return [
            'total_tentativas' => $tentativas->count(),
            'tentativas_completadas' => $tentativasCompletadas->count(),
            'tentativas_aprovadas' => $tentativasAprovadas->count(),
            'media_score' => $tentativasCompletadas->avg('simulado_tentativas.score') ?? 0,
            'tempo_total' => $tentativasCompletadas->sum('simulado_tentativas.tempo_gasto') ?? 0,
            'pontos_ganhos' => $pontosGanhos,
        ];
    }
}
