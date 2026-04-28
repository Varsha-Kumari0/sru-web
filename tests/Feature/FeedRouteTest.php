<?php

use App\Models\User;

test('feed route redirects authenticated users to dashboard', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'role' => 'alumni',
    ]);

    $response = $this->actingAs($user)->get('/feed');

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('feed route requires authentication', function () {
    $response = $this->get('/feed');

    $response->assertRedirect(route('login', absolute: false));
});
