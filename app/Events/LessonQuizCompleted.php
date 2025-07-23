<?php

namespace App\Events;

use App\Models\User;
use App\Models\LessonQuiz;
use App\Models\LessonQuizAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonQuizCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public LessonQuiz $quiz;
    public LessonQuizAttempt $attempt;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, LessonQuiz $quiz, LessonQuizAttempt $attempt)
    {
        $this->user = $user;
        $this->quiz = $quiz;
        $this->attempt = $attempt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }
}