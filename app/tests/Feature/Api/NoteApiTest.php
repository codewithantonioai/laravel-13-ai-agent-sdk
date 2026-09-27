<?php

use App\Models\Note;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guests get a 401', function () {
    $this->getJson('/api/notes')->assertUnauthorized();
});

test('users list only their own notes', function () {
    Note::factory()->count(3)->for($this->user)->create();
    Note::factory()->create();

    Sanctum::actingAs($this->user, ['notes:read']);

    $this->getJson('/api/notes')
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('meta.total', 3);
});

test('a note can be created through the API', function () {
    Sanctum::actingAs($this->user, ['notes:write']);

    $this->postJson('/api/notes', ['title' => 'Written from my phone'])
        ->assertCreated()
        ->assertJsonPath('data.title', 'Written from my phone');
});

test('validation errors come back as JSON', function () {
    Sanctum::actingAs($this->user, ['notes:write']);

    $this->postJson('/api/notes', ['title' => 'Hi'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

test('other users cannot read the note', function () {
    $note = Note::factory()->create();

    Sanctum::actingAs($this->user, ['notes:read']);

    $this->getJson("/api/notes/{$note->id}")->assertForbidden();
});

test('a read-only token cannot create notes', function () {
    Sanctum::actingAs($this->user, ['notes:read']);

    $this->postJson('/api/notes', ['title' => 'Not allowed'])
        ->assertForbidden();
});
