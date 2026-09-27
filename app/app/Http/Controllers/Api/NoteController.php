<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->notes()
            ->with('tags')
            ->latest()
            ->paginate(10)
            ->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $note = $request->user()->notes()->create(
            $request->safe()->except(['tags', 'image'])
        );

        $note->tags()->sync($request->validated('tags', []));

        return $note->load('tags')->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        Gate::authorize('view', $note);

        return $note->load('tags')->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note->update($request->safe()->except(['tags', 'image']));

        $note->tags()->sync($request->validated('tags', []));

        return $note->load('tags')->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);

        $note->delete();

        return response()->noContent();
    }
}
