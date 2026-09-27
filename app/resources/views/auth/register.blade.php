<x-layout>
    <h1 class="text-4xl font-bold">Create your account</h1>

    <form method="POST" action="/register" class="mt-8 space-y-4">
        @csrf
        <input name="name" placeholder="Name" value="{{ old('name') }}" class="field">
        @error('name')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" class="field">
        @error('email')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <input name="password" type="password" placeholder="Password" class="field">
        @error('password')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <input name="password_confirmation" type="password" placeholder="Confirm password" class="field">
        <button class="button">Register</button>
    </form>

    <p class="mt-6 text-slate-400">Already have an account? <a href="/login" class="text-cyan-400">Log in</a></p>
</x-layout>
