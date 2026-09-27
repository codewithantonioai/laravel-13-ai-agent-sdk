# Lesson 4 · Give it tools

▶ [Watch this lesson](https://youtu.be/DYQ7VNI3ZzA?t=528) (starts at 8:48)

## Goal

Lesson four. Tools, so the assistant can search Antonio's notes, and only Antonio's.

## Commands and files (as shown in the video)

### Terminal · make tools

```bash
```

### `app/Ai/Tools/SearchNotes.php` · search notes

```php
<?php

namespace App\Ai\Tools;

use App\Models\Note;
use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchNotes implements Tool
{
    public function __construct(public User $user) {}

    public function description(): Stringable|string
    {
        return 'Search the user\'s notes by a keyword in the title or content. Returns up to 5 notes.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'];

        $notes = $this->user->notes()
            ->with('tags')
            ->where(fn ($q) => $q
                ->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%"))
            ->latest()
            ->limit(5)
            ->get();

        if ($notes->isEmpty()) {
            return 'No notes found.';
        }

        return $notes->map(fn (Note $note) => [
            'id' => $note->id,
            'title' => $note->title,
            'excerpt' => Str::limit((string) $note->content, 100),
            'tags' => $note->tags->pluck('name'),
            'created_at' => $note->created_at->toDateString(),
        ])->toJson();
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('A single keyword or short phrase, like "pop filter".')
                ->required(),
        ];
    }
}
```

### `app/Ai/Tools/GetNote.php` · get note

```php
<?php

namespace App\Ai\Tools;

use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetNote implements Tool
{
    public function __construct(public User $user) {}

    public function description(): Stringable|string
    {
        return 'Get the full content of one of the user\'s notes by its id.';
    }

    public function handle(Request $request): Stringable|string
    {
        $note = $this->user->notes()->with('tags')->find($request['id']);

        if (! $note) {
            return 'Note not found.';
        }

        return json_encode([
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'tags' => $note->tags->pluck('name'),
            'remind_at' => $note->remind_at?->toDateTimeString(),
            'created_at' => $note->created_at->toDateString(),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->required(),
        ];
    }
}
```

### `app/Ai/Agents/NotesAssistant.php` · agent tools

```php
<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GetNote;
use App\Ai\Tools\SearchNotes;
use App\Models\User;
use Laravel\Ai\Attributes\MaxSteps;
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
    use Promptable;

    public function __construct(public User $user) {}

    public function instructions(): Stringable|string
    {
        return "You are a helpful assistant for the Notes app. "
            ."You help {$this->user->name} find and understand their own notes. "
            ."Use your tools to search their notes before you answer. "
            ."Answer only from their notes. If the notes don't say, say so. "
            ."Keep answers short.";
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [
            new SearchNotes($this->user),
            new GetNote($this->user),
        ];
    }
}
```

### Terminal · tinker tools

```bash
```

### Terminal · tinker ownership

```bash
```

## Recap

So: a tool is a description, a schema and a handle method. Every query goes through the user's notes relationship, so the model can't reach anyone else's. And max steps sets the ceiling. Next, answers with a shape.
