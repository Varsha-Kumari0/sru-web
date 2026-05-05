<?php

use App\Models\Message;
use App\Models\User;

it('links dashboard members to encrypted message pages', function () {
    $viewer = User::factory()->create(['role' => 'user']);
    $member = User::factory()->create(['role' => 'user', 'name' => 'Linked Alumni']);

    $response = $this->actingAs($viewer)->get(route('dashboard'));

    $response->assertOk();
    $response->assertDontSee('/messages/' . $member->id, false);

    preg_match_all('/href="([^"]*\/messages\/[^"]+)"/', $response->getContent(), $matches);
    $messageLinks = collect($matches[1] ?? [])
        ->reject(fn (string $href) => str_ends_with($href, '/messages'))
        ->values();

    expect($messageLinks)->not->toBeEmpty();
    $this->actingAs($viewer)->get($messageLinks->first())->assertOk();
});

it('returns encrypted chat urls from user search', function () {
    $viewer = User::factory()->create(['role' => 'user']);
    $member = User::factory()->create(['role' => 'user', 'name' => 'Searchable Alumni']);

    $response = $this->actingAs($viewer)
        ->getJson(route('messages.users.search', ['q' => 'Searchable']));

    $response->assertOk()
        ->assertJsonPath('users.0.name', 'Searchable Alumni');

    $chatUrl = $response->json('users.0.chat_url');
    expect($chatUrl)->not->toEndWith('/messages/' . $member->id);

    $this->actingAs($viewer)->get($chatUrl)->assertOk();
});

it('sends messages through encrypted message routes', function () {
    $sender = User::factory()->create(['role' => 'user']);
    $receiver = User::factory()->create(['role' => 'admin']);

    $this->actingAs($sender)
        ->post(route('messages.store', ['userToken' => $receiver->message_token]), [
            'content' => 'Hello from a feature test.',
        ])
        ->assertRedirect();

    expect(Message::query()
        ->where('sender_id', $sender->id)
        ->where('receiver_id', $receiver->id)
        ->where('content', 'Hello from a feature test.')
        ->exists())->toBeTrue();

    $this->actingAs($sender)->get(route('messages.show', ['userToken' => $receiver->id]))->assertNotFound();
});
