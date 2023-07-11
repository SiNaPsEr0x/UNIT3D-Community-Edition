<?php

uses(RefreshDatabase::class);

test('create returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $torrent = \App\Models\Torrent::factory()->create();
    $mediaLanguages = \App\Models\MediaLanguage::factory()->times(3)->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('subtitles.create'));

    $response->assertOk();
    $response->assertViewIs('subtitle.create');
    $response->assertViewHas('torrent', $torrent);
    $response->assertViewHas('media_languages');

    // TODO: perform additional assertions
});

test('destroy returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $subtitle = \App\Models\Subtitle::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->delete(route('subtitles.destroy', [$subtitle]));

    $response->assertOk();
    $this->assertModelMissing($subtitle);

    // TODO: perform additional assertions
});

test('destroy aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $subtitle = \App\Models\Subtitle::factory()->create();
    $user = \App\Models\User::factory()->create();

    // TODO: perform additional setup to trigger `abort_unless(403)`...

    $response = $this->actingAs($user)->delete(route('subtitles.destroy', [$subtitle]));

    $response->assertForbidden();
});

test('download returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $subtitle = \App\Models\Subtitle::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('subtitles.download', [$subtitle]));

    $response->assertOk();

    // TODO: perform additional assertions
});

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('subtitles.index'));

    $response->assertOk();
    $response->assertViewIs('subtitle.index');

    // TODO: perform additional assertions
});

test('store validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        \App\Http\Controllers\SubtitleController::class,
        'store',
        \App\Http\Requests\StoreSubtitleRequest::class
    );
});

test('store returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $torrent = \App\Models\Torrent::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post(route('subtitles.store'), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('update validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        \App\Http\Controllers\SubtitleController::class,
        'update',
        \App\Http\Requests\UpdateSubtitleRequest::class
    );
});

test('update returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $subtitle = \App\Models\Subtitle::factory()->create();
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->patch(route('subtitles.update', [$subtitle]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('update aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $subtitle = \App\Models\Subtitle::factory()->create();
    $user = \App\Models\User::factory()->create();

    // TODO: perform additional setup to trigger `abort_unless(403)`...

    $response = $this->actingAs($user)->patch(route('subtitles.update', [$subtitle]), [
        // TODO: send request data
    ]);

    $response->assertForbidden();
});

// test cases...
