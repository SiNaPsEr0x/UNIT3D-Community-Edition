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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Company.
 *
 * @property int         $id
 * @property string      $name
 * @property string|null $description
 * @property string|null $headquarters
 * @property string|null $homepage
 * @property string|null $logo
 * @property string|null $origin_country
 */
class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Torrent, $this>
     */
    public function movieTorrents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Torrent::class, 'company_movie', 'movie_id', 'company_id', 'id', 'movie_id')->whereRelation('category', 'movie_meta', '=', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Torrent, $this>
     */
    public function tvTorrents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Torrent::class, 'company_tv', 'tv_id', 'company_id', 'id', 'tv_id')->whereRelation('category', 'tv_meta', '=', true);
    }
}
