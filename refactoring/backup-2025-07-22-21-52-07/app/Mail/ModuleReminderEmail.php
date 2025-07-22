<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class ModuleReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User Usuário que receberá o lembrete
     */
    public User $user;

    /**
     * @var Collection Módulos pendentes
     */
    public Collection $modules;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Collection $modules)
    {
        $this->user = $user;
        $this->modules = $modules;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Módulos Pendentes - Continue sua jornada de aprendizado! 📚',
            tags: ['reminder', 'module', 'hcp'],
            metadata: [
                'user_id' => $this->user->id,
                'modules_count' => $this->modules->count(),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifications.module-reminder',
            with: [
                'user' => $this->user,
                'modules' => $this->modules,
                'actionUrl' => route('modules.index'),
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