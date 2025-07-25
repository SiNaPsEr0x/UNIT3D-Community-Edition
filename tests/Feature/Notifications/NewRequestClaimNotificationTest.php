<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use App\Models\Category;
use App\Models\Group;
use App\Models\Resolution;
use App\Models\TorrentRequest;
use App\Models\Torrent;
use App\Models\Type;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\NewRequestClaim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('claim a request creates a notification for the requester', function (): void {
    Notification::fake();

    $requester = User::factory()->create();
    $filler = User::factory()->create();

    $fillerNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $requester->id,
        'block_notifications' => 0,
        'show_request_claim'  => 1,
    ]);

    $category = Category::factory()->create();
    $type = Type::factory()->create();
    $resolution = Resolution::factory()->create();

    $torrent = Torrent::factory()->create([
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
    ]);

    $torrentRequest = TorrentRequest::factory()->create([
        'anon'          => false,
        'user_id'       => $requester->id,
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
        'torrent_id'    => null,
        'filled_by'     => null,
        'filled_when'   => now(),
        'approved_by'   => null,
        'approved_when' => null,
    ]);

    $response = $this->actingAs($filler)->post(route('requests.claims.store', [$torrentRequest]), [
        'anon' => 0,
    ]);

    $response->assertRedirect(route('requests.show', $torrentRequest))
        ->assertSessionHas('success', trans('request.claimed-success'));

    Notification::assertSentTo(
        [$requester],
        NewRequestClaim::class
    );
    Notification::assertCount(1);
});

test('claim a request creates a notification for the requester when claim notifications not disabled for specific group', function (): void {
    Notification::fake();

    $randomGroup = Group::factory()->create();

    $requester = User::factory()->create();
    $filler = User::factory()->create([]);

    $fillerNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $requester->id,
        'block_notifications' => 0,
        'show_request_claim'  => 1,
        'json_request_groups' => [$randomGroup->id],
    ]);

    $category = Category::factory()->create();
    $type = Type::factory()->create();
    $resolution = Resolution::factory()->create();

    $torrent = Torrent::factory()->create([
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
    ]);

    $torrentRequest = TorrentRequest::factory()->create([
        'anon'          => false,
        'user_id'       => $requester->id,
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
        'torrent_id'    => null,
        'filled_by'     => null,
        'filled_when'   => now(),
        'approved_by'   => null,
        'approved_when' => null,
    ]);

    $response = $this->actingAs($filler)->post(route('requests.claims.store', [$torrentRequest]), [
        'anon' => 0,
    ]);

    $response->assertRedirect(route('requests.show', $torrentRequest))
        ->assertSessionHas('success', trans('request.claimed-success'));

    Notification::assertSentTo(
        [$requester],
        NewRequestClaim::class
    );
    Notification::assertCount(1);
});

test('claim a request creates a notification for the requester when all notifications are disabled', function (): void {
    Notification::fake();

    $requester = User::factory()->create();
    $filler = User::factory()->create([]);

    $fillerNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $requester->id,
        'block_notifications' => 1,
        'show_request_claim'  => 1,
    ]);

    $category = Category::factory()->create();
    $type = Type::factory()->create();
    $resolution = Resolution::factory()->create();

    $torrent = Torrent::factory()->create([
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
    ]);

    $torrentRequest = TorrentRequest::factory()->create([
        'anon'          => false,
        'user_id'       => $requester->id,
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
        'torrent_id'    => null,
        'filled_by'     => null,
        'filled_when'   => now(),
        'approved_by'   => null,
        'approved_when' => null,
    ]);

    $response = $this->actingAs($filler)->post(route('requests.claims.store', [$torrentRequest]), [
        'anon' => 0,
    ]);

    $response->assertRedirect(route('requests.show', $torrentRequest))
        ->assertSessionHas('success', trans('request.claimed-success'));

    Notification::assertCount(0);
});

test('claim a request creates a notification for the requester when request claim notifications are disabled', function (): void {
    Notification::fake();

    $requester = User::factory()->create();
    $filler = User::factory()->create([]);

    $fillerNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $requester->id,
        'block_notifications' => 0,
        'show_request_claim'  => 0,
    ]);

    $category = Category::factory()->create();
    $type = Type::factory()->create();
    $resolution = Resolution::factory()->create();

    $torrent = Torrent::factory()->create([
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
    ]);

    $torrentRequest = TorrentRequest::factory()->create([
        'anon'          => false,
        'user_id'       => $requester->id,
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
        'torrent_id'    => null,
        'filled_by'     => null,
        'filled_when'   => now(),
        'approved_by'   => null,
        'approved_when' => null,
    ]);

    $response = $this->actingAs($filler)->post(route('requests.claims.store', [$torrentRequest]), [
        'anon' => 0,
    ]);

    $response->assertRedirect(route('requests.show', $torrentRequest))
        ->assertSessionHas('success', trans('request.claimed-success'));

    Notification::assertCount(0);
});

test('claim a request creates a notification for the requester when request claim notifications are disabled for specific group', function (): void {
    Notification::fake();

    $group = Group::factory()->create();

    $requester = User::factory()->create();
    $filler = User::factory()->create([
        'group_id' => $group->id,
    ]);

    $fillerNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $requester->id,
        'block_notifications' => 0,
        'show_request_claim'  => 0,
        'json_request_groups' => [$group->id],
    ]);

    $category = Category::factory()->create();
    $type = Type::factory()->create();
    $resolution = Resolution::factory()->create();

    $torrent = Torrent::factory()->create([
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
    ]);

    $torrentRequest = TorrentRequest::factory()->create([
        'anon'          => false,
        'user_id'       => $requester->id,
        'category_id'   => $category->id,
        'type_id'       => $type->id,
        'resolution_id' => $resolution->id,
        'torrent_id'    => null,
        'filled_by'     => null,
        'filled_when'   => now(),
        'approved_by'   => null,
        'approved_when' => null,
    ]);

    $response = $this->actingAs($filler)->post(route('requests.claims.store', [$torrentRequest]), [
        'anon' => 0,
    ]);

    $response->assertRedirect(route('requests.show', $torrentRequest))
        ->assertSessionHas('success', trans('request.claimed-success'));

    Notification::assertCount(0);
});
