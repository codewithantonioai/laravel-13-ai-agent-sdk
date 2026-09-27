<?php

use App\Models\Note;
use App\Models\User;
use App\Notifications\NoteReminder;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();

    $this->user = User::factory()->create();
    $this->note = Note::factory()->for($this->user)->create([
        'remind_at' => now()->addHour(),
    ]);
});

test('reminders are sent when they are due', function () {
    $this->artisan('notes:send-reminders')->assertSuccessful();
    Notification::assertNothingSent();

    $this->travel(61)->minutes();

    $this->artisan('notes:send-reminders')->assertSuccessful();
    Notification::assertSentTo($this->user, NoteReminder::class);
});

test('a reminder is only sent once', function () {
    $this->travel(61)->minutes();

    $this->artisan('notes:send-reminders');
    $this->artisan('notes:send-reminders');

    Notification::assertSentTimes(NoteReminder::class, 1);
    expect($this->note->fresh()->remind_at)->toBeNull();
});

test('reminders must be in the future', function () {
    $response = $this->actingAs($this->user)->post('/notes', [
        'title' => 'Too late',
        'remind_at' => now()->subDay(),
    ]);

    $response->assertInvalid(['remind_at']);
});
