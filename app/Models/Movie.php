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
use App\Enums\Occupation;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Movie.
 *
 * @property int                             $id
 * @property string|null                     $tmdb_id
 * @property string|null                     $imdb_id
 * @property string                          $title
 * @property string                          $title_sort
 * @property string|null                     $original_language
 * @property int|null                        $adult
 * @property string|null                     $backdrop
 * @property string|null                     $budget
 * @property string|null                     $homepage
 * @property string|null                     $original_title
 * @property string|null                     $overview
 * @property string|null                     $popularity
 * @property string|null                     $poster
 * @property \Illuminate\Support\Carbon|null $release_date
 * @property string|null                     $revenue
 * @property string|null                     $runtime
 * @property string|null                     $status
 * @property string|null                     $tagline
 * @property string|null                     $vote_average
 * @property int|null                        $vote_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $trailer
 */
class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{release_date: 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'release_date' => 'datetime',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Genre, $this>
     */
    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Person, $this>
     */
    public function people(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'credits');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Credit, $this>
     */
    public function credits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Person, $this>
     */
    public function directors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::DIRECTOR->value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Company, $this>
     */
    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Collection, $this>
     */
    public function collection(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collection::class)->take(1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Recommendation, $this>
     */
    public function recommendations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recommendation::class, 'movie_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function recommendedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, Recommendation::class, 'movie_id', 'recommendation_movie_id', 'id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent, $this>
     */
    public function torrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class, 'tmdb', 'id')->whereRelation('category', 'movie_meta', '=', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest, $this>
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class, 'tmdb', 'id')->whereRelation('category', 'movie_meta', '=', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Wish, $this>
     */
    public function wishes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wish::class);
    }
}
