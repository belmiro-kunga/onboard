<?php

namespace App\Events;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que completou o quiz
     */
    public User $user;

    /**
     * @var Quiz Quiz que foi completado
     */
    public Quiz $quiz;

    /**
     * @var QuizAttempt Tentativa do quiz
     */
    public QuizAttempt $attempt;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Quiz $quiz, QuizAttempt $attempt)
    {
        $this->user = $user;
        $this->quiz = $quiz;
        $this->attempt = $attempt;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'quiz.completed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'quiz' => [
                'id' => $this->quiz->id,
                'title' => $this->quiz->title,
                'category' => $this->quiz->category,
            ],
            'attempt' => [
                'id' => $this->attempt->id,
                'score' => $this->attempt->score,
                'passed' => $this->attempt->passed,
                'time_spent' => $this->attempt->time_spent,
            ],
            'message' => $this->attempt->passed 
                ? "Parabéns! Você foi aprovado no quiz '{$this->quiz->title}' com {$this->attempt->score}%!"
                : "Quiz '{$this->quiz->title}' completado. Continue estudando para melhorar!"
        ];
    }
}