<?php

uses(RefreshDatabase::class);

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.dashboard.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.dashboard.index');
    $response->assertViewHas('users');
    $response->assertViewHas('torrents');
    $response->assertViewHas('peers');
    $response->assertViewHas('reports');
    $response->assertViewHas('apps');
    $response->assertViewHas('certificate');
    $response->assertViewHas('uptime');
    $response->assertViewHas('ram');
    $response->assertViewHas('disk');
    $response->assertViewHas('avg');
    $response->assertViewHas('basic');
    $response->assertViewHas('file_permissions');

    // TODO: perform additional assertions
});

// test cases...
