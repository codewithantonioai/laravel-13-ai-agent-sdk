<x-layout>
    <h1 class="text-4xl font-bold">Edit note</h1>

    <form method="POST" action="{{ route('notes.summary', $note) }}" class="mt-6">
        @csrf
        <button class="rounded-xl border border-cyan-400 px-5 py-3 font-semibold text-cyan-300">
            Summarize with AI
        </button>
    </form>

    @if ($summary = session('summary'))
        <section class="mt-6 rounded-xl border border-cyan-400/40 bg-slate-900 p-5">
            <p class="text-sm font-semibold uppercase tracking-wide text-cyan-300">AI summary</p>
            <p class="mt-2 text-lg">{{ $summary['summary'] }}</p>
            <ul class="mt-4 list-disc space-y-1 pl-5 text-slate-300">
                @foreach ($summary['key_points'] as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($summary['suggested_tags'] as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
        </section>
    @endif

    <form method="POST" action="{{ route('notes.update', $note) }}"
        enctype="multipart/form-data" class="mt-8 space-y-4">
        @csrf
        @method('PATCH')
        <input name="title" value="{{ old('title', $note->title) }}" class="field">
        @error('title')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <textarea name="content" class="field">{{ old('content', $note->content) }}</textarea>
        @if ($note->image_path)
            <img src="{{ asset('storage/' . $note->image_path) }}" alt=""
                class="aspect-video w-48 rounded-lg object-cover">
        @endif
        <input type="file" name="image" accept="image/*" class="field">
        @error('image')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <label class="block text-slate-400">
            Remind me at
            <input type="datetime-local" name="remind_at"
                value="{{ old('remind_at', $note->remind_at?->format('Y-m-d\\TH:i')) }}" class="field mt-1">
        </label>
        @error('remind_at')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <div class="flex flex-wrap gap-3">
            @foreach ($tags as $tag)
                <label class="tag-option">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        @checked(in_array($tag->id, old('tags', $note->tags->pluck('id')->all())))>
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>
        <button class="button">Update note</button>
    </form>
</x-layout>
