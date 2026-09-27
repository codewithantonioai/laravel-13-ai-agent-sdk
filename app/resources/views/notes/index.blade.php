<x-layout>
    <h1 class="text-4xl font-bold">My notes</h1>

    <a href="{{ route('notes.create') }}" class="button mt-6 inline-block">New note</a>

    <nav class="mt-8 flex flex-wrap gap-2">
        <a href="{{ route('notes.index') }}"
            @class(['filter', 'filter-active' => ! request('tag')])>All</a>
        @foreach ($tags as $tag)
            <a href="{{ route('notes.index', ['tag' => $tag->name]) }}"
                @class(['filter', 'filter-active' => request('tag') === $tag->name])>
                {{ $tag->name }} · {{ $tag->notes_count }}
            </a>
        @endforeach
    </nav>

    <ul class="mt-8 space-y-3">
        @foreach ($notes as $note)
            <li class="rounded-xl bg-slate-800 p-5">
                @if ($note->image_path)
                    <img src="{{ asset('storage/' . $note->image_path) }}" alt=""
                        class="mb-4 aspect-video w-full rounded-lg object-cover">
                @endif
                <p class="text-xl">{{ $note->title }}</p>
                @if ($note->content)
                    <p class="mt-1 text-slate-400">{{ $note->content }}</p>
                @endif
                @if ($note->remind_at)
                    <p class="mt-2 text-sm text-amber-300">
                        Reminder · {{ $note->remind_at->format('M j, H:i') }}
                    </p>
                @endif
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($note->tags as $tag)
                        <a href="{{ route('notes.index', ['tag' => $tag->name]) }}"
                            class="tag">{{ $tag->name }}</a>
                    @endforeach
                </div>
                <div class="mt-3 flex gap-4">
                    @can('update', $note)
                        <a href="{{ route('notes.edit', $note) }}" class="text-cyan-400">Edit</a>
                    @endcan
                    @can('delete', $note)
                        <form method="POST"
                              action="{{ route('notes.destroy', $note) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400">Delete</button>
                        </form>
                    @endcan
                </div>
            </li>
        @endforeach
    </ul>

    <div class="mt-8">
        {{ $notes->links() }}
    </div>
</x-layout>
