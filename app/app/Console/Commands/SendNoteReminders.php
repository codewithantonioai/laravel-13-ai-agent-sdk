<?php

namespace App\Console\Commands;

use App\Models\Note;
use App\Notifications\NoteReminder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('notes:send-reminders')]
#[Description('Send the reminders that are due')]
class SendNoteReminders extends Command
{
    public function handle(): void
    {
        $notes = Note::with('user')
            ->where('remind_at', '<=', now())
            ->get();

        foreach ($notes as $note) {
            $note->user->notify(new NoteReminder($note));

            $note->update(['remind_at' => null]);
        }

        $this->info("Reminders sent: {$notes->count()}");
    }
}
