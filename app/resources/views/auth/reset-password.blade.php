<x-layout>
    <h1 class="text-4xl font-bold">Choose a new password</h1>

    <form method="POST" action="{{ route('password.update') }}"
          class="mt-8 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input name="email" type="email" placeholder="Email"
               value="{{ old('email', request('email')) }}" class="field">
        @error('email')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <input name="password" type="password" placeholder="New password"
               class="field">
        @error('password')
            <p class="text-red-400">{{ $message }}</p>
        @enderror
        <input name="password_confirmation" type="password"
               placeholder="Confirm new password" class="field">
        <button class="button">Reset password</button>
    </form>
</x-layout>
