<?php

uses(RefreshDatabase::class);

test('store validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        \App\Http\Controllers\Staff\UnbanController::class,
        'store',
        \App\Http\Requests\Staff\StoreUnbanRequest::class
    );
});

test('store returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();
    $authUser = \App\Models\User::factory()->create();

    $response = $this->actingAs($authUser)->post(route('staff.unbans.store'), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('store aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();
    $authUser = \App\Models\User::factory()->create();

    // TODO: perform additional setup to trigger `abort_if(403)`...

    $response = $this->actingAs($authUser)->post(route('staff.unbans.store'), [
        // TODO: send request data
    ]);

    $response->assertForbidden();
});

// test cases...
