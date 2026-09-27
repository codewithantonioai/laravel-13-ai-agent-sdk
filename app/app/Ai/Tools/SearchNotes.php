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

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search the user\'s notes by a keyword in the title or content. Returns up to 5 notes.';
    }

    /**
     * Execute the tool.
     */
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

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('A single keyword or short phrase, like "pop filter".')
                ->required(),
        ];
    }
}
