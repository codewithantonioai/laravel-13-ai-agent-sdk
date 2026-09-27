<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\Tries;

#[Tries(3)]
#[Backoff(5)]
class NoteReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Note $note)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: '.$this->note->title)
            ->greeting('Hi '.$notifiable->name.'!')
            ->line('You asked us to remind you about this note:')
            ->line('"'.$this->note->title.'"')
            ->action('Open the note', route('notes.edit', $this->note))
            ->line('Have a productive day!');
    }
}
