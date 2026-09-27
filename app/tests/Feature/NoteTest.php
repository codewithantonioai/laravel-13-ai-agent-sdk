<?php

use App\Models\Note;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/notes');

    $response->assertRedirect('/login');
});

test('users see their own notes', function () {
    $user = User::factory()->create();
    Note::factory()->for($user)->create(['title' => 'Buy a pop filter']);

    $response = $this->actingAs($user)->get('/notes');

    $response->assertOk()->assertSee('Buy a pop filter');
});

test('users do not see notes from other users', function () {
    $user = User::factory()->create();
    Note::factory()->create(['title' => 'Plan the surprise party']);

    $response = $this->actingAs($user)->get('/notes');

    $response->assertOk()->assertDontSee('Plan the surprise party');
});

test('unverified users must verify their email first', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/notes');

    $response->assertRedirect('/email/verify');
});
