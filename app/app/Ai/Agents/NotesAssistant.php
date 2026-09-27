<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GetNote;
use App\Ai\Tools\SearchNotes;
use App\Models\User;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

#[MaxSteps(5)]
class NotesAssistant implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(public User $user) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return "You are a helpful assistant for the Notes app. "
            ."You help {$this->user->name} find and understand their own notes. "
            ."Use your tools to search their notes before you answer. "
            ."Answer only from their notes. If the notes don't say, say so. "
            ."Keep answers short, in plain text without Markdown.";
    }

    /**
     * Get the tools available to the agent.
     *
     * @return list<Agent|Tool|ProviderTool>
     */
    public function tools(): iterable
    {
        return [
            new SearchNotes($this->user),
            new GetNote($this->user),
        ];
    }
}
