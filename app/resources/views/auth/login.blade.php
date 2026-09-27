<x-layout>
    <h1 class="text-4xl font-bold">Log in</h1>

    <form method="POST" action="/login" class="mt-8 space-y-4">
        @csrf
        <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" class="field">
        @error('email')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <input name="password" type="password" placeholder="Password" class="field">
        @error('password')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <button class="button">Log in</button>
    </form>

    <p class="mt-6"><a href="{{ route('password.request') }}"
        class="text-cyan-400">Forgot your password?</a></p>

    <p class="mt-2 text-slate-400">No account yet? <a href="/register" class="text-cyan-400">Register</a></p>
</x-layout>
