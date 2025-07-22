<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AchievementEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User Usuário que conquistou a achievement
     */
    public User $user;

    /**
     * @var Achievement Achievement conquistada
     */
    public Achievement $achievement;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Achievement $achievement)
    {
        $this->user = $user;
        $this->achievement = $achievement;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏆 Nova Conquista Desbloqueada! - Parabéns!',
            tags: ['achievement', 'gamification', 'hcp'],
            metadata: [
                'user_id' => $this->user->id,
                'achievement_id' => $this->achievement->id,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifications.achievement',
            with: [
                'user' => $this->user,
                'achievement' => $this->achievement,
                'actionUrl' => route('gamification.achievements'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
} 