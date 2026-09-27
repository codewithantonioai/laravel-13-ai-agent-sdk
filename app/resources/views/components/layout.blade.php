<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notes</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <nav class="mx-auto flex max-w-2xl items-center justify-between p-6">
        <a href="/notes" class="font-bold">Notes</a>
        @auth
            <div class="flex items-center gap-4">
                <a href="{{ route('assistant') }}" class="text-cyan-400">Assistant</a>
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-cyan-400">Log out</button>
                </form>
            </div>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="text-cyan-400">Log in</a>
        @endguest
    </nav>
    <main class="mx-auto max-w-2xl px-12 pb-12">
        @if (session('status'))
            <p class="mb-6 rounded-xl bg-emerald-900 p-4">{{ session('status') }}</p>
        @endif
        {{ $slot }}
    </main>
</body>
</html>
