<?php

namespace App\Http\Controllers;

use App\Events\VideoWatched;
use App\Services\ActivityTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoTrackingController extends Controller
{
    protected ActivityTrackingService $activityTracker;

    public function __construct(ActivityTrackingService $activityTracker)
    {
        $this->activityTracker = $activityTracker;
    }

    /**
     * Rastrear progresso do vídeo
     */
    public function trackProgress(Request $request)
    {
        $request->validate([
            'module_id' => 'required|integer',
            'video_id' => 'required|string',
            'video_title' => 'required|string',
            'current_time' => 'required|numeric|min:0',
            'total_duration' => 'required|numeric|min:1',
            'youtube_id' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        try {
            $moduleId = $request->module_id;
            $videoId = $request->video_id;
            $videoTitle = $request->video_title;
            $currentTime = $request->current_time;
            $totalDuration = $request->total_duration;
            $completionPercentage = ($currentTime / $totalDuration) * 100;

            // Só processar se assistiu uma porcentagem significativa
            if ($completionPercentage >= 80) {
                // Disparar evento de vídeo assistido
                event(new VideoWatched(
                    $user,
                    $moduleId,
                    $videoId,
                    $videoTitle,
                    $currentTime,
                    $totalDuration
                ));

                // Rastrear login diário (já que o usuário está ativo)
                $this->activityTracker->trackDailyLogin($user);

                return response()->json([
                    'success' => true,
                    'message' => 'Progresso do vídeo registrado com sucesso!',
                    'completion_percentage' => round($completionPercentage, 1),
                    'points_earned' => $this->calculateVideoPoints($totalDuration, $completionPercentage)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Progresso registrado',
                'completion_percentage' => round($completionPercentage, 1)
            ]);

        } catch (\Exception $e) {
            Log::error('Error tracking video progress', [
                'user_id' => $user->id,
                'module_id' => $request->module_id,
                'video_id' => $request->video_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar progresso do vídeo'
            ], 500);
        }
    }

    /**
     * Marcar vídeo como completado
     */
    public function markCompleted(Request $request)
    {
        $request->validate([
            'module_id' => 'required|integer',
            'video_id' => 'required|string',
            'video_title' => 'required|string',
            'total_duration' => 'required|numeric|min:1',
            'watch_time' => 'required|numeric|min:0'
        ]);

        $user = Auth::user();

        try {
            // Disparar evento de vídeo completado
            event(new VideoWatched(
                $user,
                $request->module_id,
                $request->video_id,
                $request->video_title,
                $request->watch_time,
                $request->total_duration
            ));

            $points = $this->calculateVideoPoints($request->total_duration, 100);

            return response()->json([
                'success' => true,
                'message' => 'Vídeo marcado como completado!',
                'points_earned' => $points
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking video as completed', [
                'user_id' => $user->id,
                'module_id' => $request->module_id,
                'video_id' => $request->video_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar vídeo como completado'
            ], 500);
        }
    }

    /**
     * Calcular pontos do vídeo
     */
    private function calculateVideoPoints($duration, $completionPercentage): int
    {
        $basePoints = 15;
        
        // Bônus por duração do vídeo (em segundos)
        $durationMinutes = $duration / 60;
        if ($durationMinutes > 30) {
            $basePoints += 20;
        } elseif ($durationMinutes > 15) {
            $basePoints += 10;
        }
        
        // Bônus por completar totalmente
        if ($completionPercentage >= 95) {
            $basePoints += 10;
        }
        
        return $basePoints;
    }
}