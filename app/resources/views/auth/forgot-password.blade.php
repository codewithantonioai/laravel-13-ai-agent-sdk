<x-layout>
    <h1 class="text-4xl font-bold">Forgot your password?</h1>
    <p class="mt-4 text-slate-400">
        Tell us your email and we'll send you a reset link.
    </p>

    <form method="POST" action="{{ route('password.email') }}"
          class="mt-8 space-y-4">
        @csrf
        <input name="email" type="email" placeholder="Email"
               value="{{ old('email') }}" class="field">
        @error('email')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <button class="button">Email me a reset link</button>
    </form>
</x-layout>
