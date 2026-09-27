<?php

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
});

test('an uploaded image is stored as a 1200 px WebP', function () {
    $photo = UploadedFile::fake()->image('desk.jpg', 3000, 2000);

    $this->actingAs($this->user)->post('/notes', [
        'title' => 'Buy a pop filter',
        'image' => $photo,
    ]);

    $path = Note::first()->image_path;
    Storage::disk('public')->assertExists($path);
    expect($path)->toEndWith('.webp');
    expect(getimagesize(Storage::disk('public')->path($path))[0])->toBe(1200);
});

test('a file that is not an image is rejected', function () {
    $pdf = UploadedFile::fake()->create('invoice.pdf', 100, 'application/pdf');

    $response = $this->actingAs($this->user)->post('/notes', [
        'title' => 'Pay the invoice',
        'image' => $pdf,
    ]);

    $response->assertInvalid(['image' => 'must be an image']);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});

test('deleting a note deletes its image', function () {
    $path = UploadedFile::fake()->image('old.jpg')->store('notes', 'public');
    $note = Note::factory()->for($this->user)->create(['image_path' => $path]);

    $this->actingAs($this->user)->delete("/notes/{$note->id}");

    Storage::disk('public')->assertMissing($path);
});
