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

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wish.
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property string                          $title
 * @property int                             $tmdb_movie_id
 * @property int                             $tmdb_tv_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Wish extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\WishFactory> */
    use HasFactory;

    /**
     * The Attributes That Aren't Mass Assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Has many torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent, $this>
     */
    public function movieTorrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class, 'tmdb_movie_id', 'tmdb_movie_id')
            ->whereRelation('category', 'movie_meta', '=', true);
    }

    /**
     * Has many torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent, $this>
     */
    public function tvTorrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class, 'tmdb_tv_id', 'tmdb_tv_id')
            ->whereRelation('category', 'tv_meta', '=', true);
    }
}
