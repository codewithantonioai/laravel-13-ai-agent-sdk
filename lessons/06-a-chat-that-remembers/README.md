# Lesson 6 · A chat that remembers

▶ [Watch this lesson](https://youtu.be/DYQ7VNI3ZzA?t=917) (starts at 15:17)

## Goal

Lesson six. A chat page that remembers what you said.

## Commands and files (as shown in the video)

### `app/Ai/Agents/NotesAssistant.php` · traits

```php
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
use Laravel\Ai\Messages\Message;
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
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
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
```

### Terminal · tinker memory broken

```bash
php artisan tinker
$antonio = User::firstWhere('email', 'antonio@example.com'); $antonio->name
$assistant = NotesAssistant::make(user: $antonio); count($assistant->tools())
$first = $assistant->forUser($antonio)->prompt('What do I have to record?'); $first->text
$id = $first->conversationId
$next = 'And which of those is the most recent?';
$second = $assistant->continue($id, as: $antonio)->prompt($next); $second->text
```

### `app/Ai/Agents/NotesAssistant.php` · delete messages

```php
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
use Laravel\Ai\Messages\Message;
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
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
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
```

### Terminal · tinker memory

```bash
php artisan tinker
$antonio = User::firstWhere('email', 'antonio@example.com'); $antonio->name
$assistant = NotesAssistant::make(user: $antonio); count($assistant->tools())
$first = $assistant->forUser($antonio)->prompt('What do I have to record?'); $first->text
$id = $first->conversationId
$next = 'And which of those is the most recent?';
$second = $assistant->continue($id, as: $antonio)->prompt($next); $second->text
DB::table('agent_conversation_messages')->where('conversation_id', $id)->pluck('role')
$antonio->conversations()->pluck('title')
```

### `app/Http/Controllers/AssistantController.php` · assistant controller

```php
<?php

namespace App\Http\Controllers;

use App\Ai\Agents\NotesAssistant;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index(Request $request)
    {
        $conversation = $request->user()->conversations()
            ->find($request->session()->get('conversation_id'));

        return view('assistant', [
            'messages' => $conversation?->messages()->oldest()->get() ?? [],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['question' => ['required', 'string', 'max:500']]);

        $user = $request->user();

        $conversation = $user->conversations()
            ->find($request->session()->get('conversation_id'));

        $response = NotesAssistant::make(user: $user)
            ->continueOrStart($conversation?->id, as: $user)
            ->prompt($request->input('question'));

        $request->session()->put('conversation_id', $response->conversationId);

        return to_route('assistant');
    }
}
```

### `resources/views/assistant.blade.php` · assistant view

```blade
<x-layout>
    <h1 class="text-4xl font-bold">Assistant</h1>
    <p class="mt-2 text-slate-400">Ask anything about your notes.</p>

    <div class="mt-8 space-y-4">
        @foreach ($messages as $message)
            <p @class([
                'whitespace-pre-line rounded-xl p-4',
                'ml-12 bg-cyan-400/15 text-cyan-100' => $message->role === 'user',
                'mr-12 bg-slate-800' => $message->role === 'assistant',
            ])>{{ $message->content }}</p>
        @endforeach
    </div>

    <form method="POST" action="{{ route('assistant.store') }}" class="mt-8 flex gap-3">
        @csrf
        <input name="question" placeholder="What did I write about...?" class="field" autofocus>
        <button class="button">Send</button>
    </form>
    @error('question')
        <p class="mt-2 text-red-400">{{ $message }}</p>
    @enderror
</x-layout>
```

## Recap

So: remembers conversations stores every turn in the database. Delete the stub's messages method, or it silently forgets. And find conversations through the user before continuing them. Last step: tests.
