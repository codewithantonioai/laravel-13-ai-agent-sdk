<x-layout>
    <h1 class="text-4xl font-bold">New note</h1>

    <form method="POST" action="/notes" enctype="multipart/form-data"
        class="mt-8 space-y-4">
        @csrf
        <input name="title" placeholder="Title" value="{{ old('title') }}" class="field">
        @error('title')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <textarea name="content" placeholder="Content" class="field">{{ old('content') }}</textarea>
        <input type="file" name="image" accept="image/*" class="field">
        @error('image')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <label class="block text-slate-400">
            Remind me at
            <input type="datetime-local" name="remind_at"
                value="{{ old('remind_at') }}" class="field mt-1">
        </label>
        @error('remind_at')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <div class="flex flex-wrap gap-3">
            @foreach ($tags as $tag)
                <label class="tag-option">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        @checked(in_array($tag->id, old('tags', [])))>
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>
        <button class="button">Save note</button>
    </form>
</x-layout>
