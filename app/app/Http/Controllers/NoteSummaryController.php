<?php

namespace App\Http\Controllers;

use App\Ai\Agents\NoteSummarizer;
use App\Models\Note;
use Illuminate\Support\Facades\Gate;

class NoteSummaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Note $note)
    {
        Gate::authorize('view', $note);

        $response = NoteSummarizer::make()
            ->prompt("{$note->title}\n\n{$note->content}");

        return to_route('notes.edit', $note)
            ->with('summary', $response->toArray());
    }
}
