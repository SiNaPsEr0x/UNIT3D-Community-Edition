<?php

uses(RefreshDatabase::class);

test('create returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.blacklisted_clients.create'));

    $response->assertOk();
    $response->assertViewIs('Staff.blacklist.clients.create');

    // TODO: perform additional assertions
});

test('destroy returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $blacklistClient = \App\Models\BlacklistClient::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->delete(route('staff.blacklisted_clients.destroy', [$blacklistClient]));

    $response->assertOk();
    $this->assertModelMissing($blacklistClient);

    // TODO: perform additional assertions
});

test('edit returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $blacklistClient = \App\Models\BlacklistClient::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.blacklisted_clients.edit', [$blacklistClient]));

    $response->assertOk();
    $response->assertViewIs('Staff.blacklist.clients.edit');
    $response->assertViewHas('client');

    // TODO: perform additional assertions
});

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $blacklistClients = \App\Models\BlacklistClient::factory()->times(3)->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.blacklisted_clients.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.blacklist.clients.index');
    $response->assertViewHas('clients');

    // TODO: perform additional assertions
});

test('store validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        \App\Http\Controllers\Staff\BlacklistClientController::class,
        'store',
        \App\Http\Requests\Staff\StoreBlacklistClientRequest::class
    );
});

test('store returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.blacklisted_clients.store'), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('update validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        \App\Http\Controllers\Staff\BlacklistClientController::class,
        'update',
        \App\Http\Requests\Staff\UpdateBlacklistClientRequest::class
    );
});

test('update returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $blacklistClient = \App\Models\BlacklistClient::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->patch(route('staff.blacklisted_clients.update', [$blacklistClient]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

// test cases...
