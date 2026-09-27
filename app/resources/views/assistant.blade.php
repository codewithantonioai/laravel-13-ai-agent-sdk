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
