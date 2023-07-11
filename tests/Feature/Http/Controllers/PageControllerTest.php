<?php

uses(RefreshDatabase::class);

test('about returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('about'));

    $response->assertOk();
    $response->assertViewIs('page.aboutus');

    // TODO: perform additional assertions
});

test('clientblacklist returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $blacklistClients = \App\Models\BlacklistClient::factory()->times(3)->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('client_blacklist'));

    $response->assertOk();
    $response->assertViewIs('page.blacklist.client');
    $response->assertViewHas('clients');

    // TODO: perform additional assertions
});

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $pages = \App\Models\Page::factory()->times(3)->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.index'));

    $response->assertOk();
    $response->assertViewIs('page.index');
    $response->assertViewHas('pages', $pages);

    // TODO: perform additional assertions
});

test('internal returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $internals = \App\Models\Internal::factory()->times(3)->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('internal'));

    $response->assertOk();
    $response->assertViewIs('page.internal');
    $response->assertViewHas('internals', $internals);

    // TODO: perform additional assertions
});

test('show returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $page = \App\Models\Page::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.show', [$page]));

    $response->assertOk();
    $response->assertViewIs('page.page');
    $response->assertViewHas('page', $page);

    // TODO: perform additional assertions
});

test('staff returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $groups = \App\Models\Group::factory()->times(3)->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff'));

    $response->assertOk();
    $response->assertViewIs('page.staff');
    $response->assertViewHas('staff');

    // TODO: perform additional assertions
});

// test cases...
