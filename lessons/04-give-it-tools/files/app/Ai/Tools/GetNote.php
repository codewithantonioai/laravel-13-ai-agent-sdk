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
