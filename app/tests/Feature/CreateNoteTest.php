<?php

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

test('users can create a note with tags', function () {
    $user = User::factory()->create();
    $tag = Tag::create(['name' => 'testing']);

    $response = $this->actingAs($user)->post('/notes', [
        'title' => 'Write the Pest tutorial',
        'content' => 'Feature tests first.',
        'tags' => [$tag->id],
    ]);

    $response->assertRedirect('/notes');
    $this->assertDatabaseHas('notes', [
        'title' => 'Write the Pest tutorial',
        'user_id' => $user->id,
    ]);
    expect(Note::first()->tags->pluck('name')->all())->toBe(['testing']);
});

test('a note needs a valid title', function (string $title) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/notes', ['title' => $title]);

    $response->assertInvalid(['title']);
    $this->assertDatabaseCount('notes', 0);
})->with([
    'empty' => '',
    'too short' => 'ab',
    'too long' => str_repeat('a', 256),
]);
