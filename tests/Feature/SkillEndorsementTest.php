<?php

use App\Models\Skill;
use App\Models\User;

test('users can endorse and remove endorsements for another alumni skill', function () {
    $owner = User::factory()->create();
    $endorser = User::factory()->create();
    $skill = Skill::create([
        'user_id' => $owner->id,
        'name' => 'Laravel',
        'level' => 'advanced',
        'endorsements' => 0,
        'endorsements_count' => 0,
    ]);

    $this->actingAs($endorser)
        ->postJson(route('skills.endorse', $skill))
        ->assertOk()
        ->assertJson([
            'success' => true,
            'endorsements_count' => 1,
        ]);

    expect($skill->refresh()->endorsements_count)->toBe(1)
        ->and($skill->endorsements)->toBe(1);

    $this->actingAs($endorser)
        ->deleteJson(route('skills.remove-endorsement', $skill))
        ->assertOk()
        ->assertJson([
            'success' => true,
            'endorsements_count' => 0,
        ]);

    expect($skill->refresh()->endorsements_count)->toBe(0)
        ->and($skill->endorsements)->toBe(0);
});

test('users cannot endorse their own skill', function () {
    $user = User::factory()->create();
    $skill = Skill::create([
        'user_id' => $user->id,
        'name' => 'PHP',
        'level' => 'expert',
        'endorsements' => 0,
        'endorsements_count' => 0,
    ]);

    $this->actingAs($user)
        ->postJson(route('skills.endorse', $skill))
        ->assertStatus(422)
        ->assertJson([
            'error' => 'You cannot endorse your own skill',
        ]);

    expect($skill->refresh()->endorsements_count)->toBe(0)
        ->and($skill->endorsements)->toBe(0);
});
