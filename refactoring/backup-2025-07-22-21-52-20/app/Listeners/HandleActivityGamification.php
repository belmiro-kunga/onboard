<?php

namespace App\Listeners;

use App\Events\QuizCompleted;
use App\Events\VideoWatched;
use App\Events\ModuleCompleted;
use App\Events\OccupationalQualifierCompleted;
use App\Events\SimuladoCompleted;
use App\Services\ActivityTrackingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleActivityGamification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var ActivityTrackingService Serviço para rastreamento de atividades
     */
    protected ActivityTrackingService $activityTracker;

    /**
     * Create the event listener.
     */
    public function __construct(ActivityTrackingService $activityTracker)
    {
        $this->activityTracker = $activityTracker;
    }

    /**
     * Handle quiz completion
     */
    public function handleQuizCompleted(QuizCompleted $event): void
    {
        try {
            $this->activityTracker->trackQuizCompletion(
                $event->user,
                $event->quiz,
                $event->attempt
            );
        } catch (\Exception $e) {
            Log::error('Failed to handle quiz completion gamification', [
                'user_id' => $event->user->id,
                'quiz_id' => $event->quiz->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle video watched
     */
    public function handleVideoWatched(VideoWatched $event): void
    {
        try {
            $this->activityTracker->trackVideoCompletion(
                $event->user,
                $event->moduleId,
                $event->videoId,
                $event->watchTime,
                $event->totalDuration
            );
        } catch (\Exception $e) {
            Log::error('Failed to handle video watched gamification', [
                'user_id' => $event->user->id,
                'video_id' => $event->videoId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle module completion
     */
    public function handleModuleCompleted(ModuleCompleted $event): void
    {
        try {
            $this->activityTracker->trackModuleCompletion(
                $event->user,
                $event->module
            );
        } catch (\Exception $e) {
            Log::error('Failed to handle module completion gamification', [
                'user_id' => $event->user->id,
                'module_id' => $event->module->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle occupational qualifier completion
     */
    public function handleQualifierCompleted(OccupationalQualifierCompleted $event): void
    {
        try {
            $this->activityTracker->trackOccupationalQualifier(
                $event->user,
                $event->qualifierId,
                $event->qualifierTitle,
                $event->score,
                $event->category,
                $event->level
            );
        } catch (\Exception $e) {
            Log::error('Failed to handle occupational qualifier completion gamification', [
                'user_id' => $event->user->id,
                'qualifier_id' => $event->qualifierId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle simulado completion
     */
    public function handleSimuladoCompleted(SimuladoCompleted $event): void
    {
        try {
            $this->activityTracker->trackSimuladoCompletion(
                $event->user,
                $event->simulado,
                $event->tentativa
            );
        } catch (\Exception $e) {
            Log::error('Failed to handle simulado completion gamification', [
                'user_id' => $event->user->id,
                'simulado_id' => $event->simulado->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}