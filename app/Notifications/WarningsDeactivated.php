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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Notifications;

use App\Interfaces\SystemNotificationInterface;
use App\Models\User;
use App\Notifications\Channels\SystemNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WarningsDeactivated extends Notification implements ShouldQueue, SystemNotificationInterface
{
    use Queueable;

    public function __construct(public User $staff)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return class-string
     */
    public function via(object $notifiable): string
    {
        return SystemNotificationChannel::class;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toSystemNotification(User $notifiable): array
    {
        return [
            'subject' => 'All Hit and Run Warnings Deactivated',
            'message' => "{$this->staff->username} has decided to deactivate all of your warnings. You lucked out!",
        ];
    }
}
