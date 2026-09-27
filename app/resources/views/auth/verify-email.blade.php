<x-layout>
    <h1 class="text-4xl font-bold">Check your inbox</h1>
    <p class="mt-4 text-slate-400">We sent a verification link to {{ auth()->user()->email }}. Click it to start writing notes.</p>

    <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
        @csrf
        <button class="button">Resend the email</button>
    </form>
</x-layout>
