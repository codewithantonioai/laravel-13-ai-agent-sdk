<?php

namespace App\Ai\Agents;

use App\Models\User;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

class NotesAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(public User $user) {}

    public function instructions(): Stringable|string
    {
        return "You are a helpful assistant for the Notes app. "
            ."You help {$this->user->name} find and understand their own notes. "
            ."Answer only from their notes. If the notes don't say, say so. "
            ."Keep answers short.";
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [];
    }
}
