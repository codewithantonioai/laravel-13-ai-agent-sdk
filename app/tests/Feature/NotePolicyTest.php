<?php

use App\Models\Note;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->note = Note::factory()->for($this->owner)->create();
});

test('owners can update their notes', function () {
    $response = $this->actingAs($this->owner)
        ->put("/notes/{$this->note->id}", ['title' => 'Updated title']);

    $response->assertRedirect('/notes');
    expect($this->note->fresh()->title)->toBe('Updated title');
});

test('other users cannot update the note', function () {
    $intruder = User::factory()->create();

    $response = $this->actingAs($intruder)
        ->put("/notes/{$this->note->id}", ['title' => 'Hacked']);

    $response->assertForbidden();
    expect($this->note->fresh()->title)->not->toBe('Hacked');
});

test('other users cannot delete the note', function () {
    $intruder = User::factory()->create();

    $response = $this->actingAs($intruder)->delete("/notes/{$this->note->id}");

    $response->assertForbidden();
    $this->assertModelExists($this->note);
});

test('owners can delete their notes', function () {
    $response = $this->actingAs($this->owner)->delete("/notes/{$this->note->id}");

    $response->assertRedirect('/notes');
    $this->assertModelMissing($this->note);
});
