<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = $request->user()->notes()
            ->with('tags')
            ->when($request->query('tag'), function ($query, $tag) {
                $query->whereHas('tags', fn ($query) => $query->where('name', $tag));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tags = Tag::withCount([
            'notes' => fn ($query) => $query->whereBelongsTo($request->user()),
        ])->orderBy('name')->get();

        return view('notes.index', ['notes' => $notes, 'tags' => $tags]);
    }

    public function create()
    {
        return view('notes.create', ['tags' => Tag::orderBy('name')->get()]);
    }

    public function store(StoreNoteRequest $request)
    {
        $data = $request->safe()->except(['tags', 'image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request);
        }

        $note = $request->user()->notes()->create($data);

        $note->tags()->sync($request->validated('tags', []));

        return redirect()->route('notes.index');
    }

    public function edit(Note $note)
    {
        Gate::authorize('update', $note);

        return view('notes.edit', [
            'note' => $note,
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $data = $request->safe()->except(['tags', 'image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request);
        }

        $note->update($data);

        $note->tags()->sync($request->validated('tags', []));

        return redirect()->route('notes.index');
    }

    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);

        $note->delete();

        return redirect()->route('notes.index')->with('status', 'Note deleted.');
    }

    private function storeImage(Request $request): string
    {
        return $request->image('image')
            ->scale(width: 1200)
            ->toWebp()
            ->quality(80)
            ->store('notes', 'public');
    }
}
