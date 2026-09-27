<?php

use App\Ai\Agents\NoteSummarizer;
use App\Ai\Agents\NotesAssistant;
use App\Ai\Tools\SearchNotes;
use App\Models\Note;
use App\Models\User;
use Laravel\Ai\Prompts\AgentPrompt;
use Laravel\Ai\Tools\Request;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('owners can summarize a note', function () {
    NoteSummarizer::fake([
        [
            'summary' => 'A plan for the last video.',
            'key_points' => ['Record on Monday'],
            'suggested_tags' => ['video'],
        ],
    ]);

    $note = Note::factory()->for($this->user)->create(['title' => 'Plan the AI video']);

    $this->actingAs($this->user)
        ->followingRedirects()
        ->post("/notes/{$note->id}/summary")
        ->assertOk()
        ->assertSee('A plan for the last video.')
        ->assertSee('Record on Monday');

    NoteSummarizer::assertPrompted(
        fn (AgentPrompt $prompt) => $prompt->contains('Plan the AI video')
    );
});

test('other users cannot summarize the note', function () {
    NoteSummarizer::fake();

    $note = Note::factory()->create();

    $this->actingAs($this->user)
        ->post("/notes/{$note->id}/summary")
        ->assertForbidden();

    NoteSummarizer::assertNeverPrompted();
});

test('the assistant remembers the conversation', function () {
    NotesAssistant::fake([
        'You wrote to buy a pop filter.',
        'Pop filter notes', // the conversation title
        'The newest one is from September 23.',
    ]);

    $this->actingAs($this->user)
        ->post('/assistant', ['question' => 'What did I write about the pop filter?'])
        ->assertRedirect('/assistant');

    $this->actingAs($this->user)
        ->followingRedirects()
        ->post('/assistant', ['question' => 'And which of those is the most recent?'])
        ->assertSeeInOrder([
            'What did I write about the pop filter?',
            'You wrote to buy a pop filter.',
            'And which of those is the most recent?',
            'The newest one is from September 23.',
        ]);

    NotesAssistant::assertPromptedTimes(2);
    expect($this->user->conversations()->sole()->title)->toBe('Pop filter notes');
});

test('the search tool only finds your own notes', function () {
    Note::factory()->for($this->user)->create(['title' => 'Buy a pop filter']);
    Note::factory()->create(['title' => 'Buy a pop filter too']);

    $result = (new SearchNotes($this->user))
        ->handle(new Request(['query' => 'pop filter']));

    expect(json_decode($result, true))->toHaveCount(1);
});
