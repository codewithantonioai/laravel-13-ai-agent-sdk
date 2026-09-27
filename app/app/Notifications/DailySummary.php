<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailySummary extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Collection $notes)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Your reminders for today')
            ->greeting('Good morning, '.$notifiable->name.'!')
            ->line('Here is what you wanted to remember today:');

        foreach ($this->notes as $note) {
            $message->line('• '.$note->remind_at->format('H:i').' · '.$note->title);
        }

        return $message->action('Open my notes', route('notes.index'));
    }
}
