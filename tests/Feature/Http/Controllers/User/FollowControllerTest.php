<?php

uses(RefreshDatabase::class);

test('destroy returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();
    $authUser = \App\Models\User::factory()->create();

    $response = $this->actingAs($authUser)->delete(route('users.followers.destroy', [$user]));

    $response->assertOk();
    $this->assertModelMissing($user);

    // TODO: perform additional assertions
});

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();
    $authUser = \App\Models\User::factory()->create();

    $response = $this->actingAs($authUser)->get(route('users.followers.index', [$user]));

    $response->assertOk();
    $response->assertViewIs('user.follower.index');
    $response->assertViewHas('followers');
    $response->assertViewHas('user', $user);

    // TODO: perform additional assertions
});

test('store returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();
    $authUser = \App\Models\User::factory()->create();

    $response = $this->actingAs($authUser)->post(route('users.followers.store', [$user]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

// test cases...
