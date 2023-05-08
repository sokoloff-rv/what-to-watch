<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    public const STATUS_READY = 'ready';
    public const STATUS_PENDING = 'pending';
    public const STATUS_MODERATE = 'moderate';

    public const ORDER_BY_RELEASED = 'released';
    public const ORDER_BY_RATING = 'rating';

    public const ORDER_TO_ASC = 'asc';
    public const ORDER_TO_DESC = 'desc';

    protected $fillable = [
        'name',
        'poster_image',
        'preview_image',
        'background_image',
        'background_color',
        'video_link',
        'preview_video_link',
        'description',
        'director',
        'released',
        'run_time',
        'rating',
        'scores_count',
        'imdb_id',
        'status',
    ];

    protected $casts = [
        'released' => 'integer',
        'rating' => 'float',
    ];

    protected $appends = [
        'starring',
        'genre',
    ];

    protected $hidden = [
        'actors',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'film_genre', 'film_id', 'genre_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_film');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function calculateRating()
    {
        $averageRating = $this->comments()->avg('rating');
        $averageRating = $averageRating ? round($averageRating, 1) : 0;

        $this->saveRating($averageRating);
    }

    public function saveRating(float $rating)
    {
        $this->rating = $rating;
        $this->save();
    }

    public static function createFromData(array $data): Film
    {
        $film = new self();
        $film->name = $data['Title'];
        $film->poster_image = $data['Poster'];

        $film->preview_image = null;
        $film->background_image = null;
        $film->background_color = null;
        $film->video_link = null;
        $film->preview_video_link = null;

        $film->description = $data['Plot'];
        $film->director = $data['Director'];
        $film->released = (int) \DateTime::createFromFormat('d M Y', $data['Released'])->format('Y');
        $film->run_time = intval($data['Runtime']);
        $film->rating = floatval($data['imdbRating']);
        $film->scores_count = intval(str_replace(',', '', $data['imdbVotes']));
        $film->imdb_id = $data['imdbID'];
        $film->status = self::STATUS_PENDING;
        $film->save();

        $actors = array_map('trim', explode(',', $data['Actors']));
        foreach ($actors as $actorName) {
            $actor = Actor::firstOrCreate(['name' => $actorName]);
            $film->actors()->attach($actor);
        }

        $genres = array_map('trim', explode(',', $data['Genre']));
        foreach ($genres as $genreName) {
            $genre = Genre::firstOrCreate(['name' => $genreName]);
            $film->genres()->attach($genre);
        }

        return $film;
    }

    public function getStarringAttribute(): array
    {
        return $this->actors()->pluck('name')->toArray();
    }

    public function getGenreAttribute(): array
    {
        return $this->genres()->pluck('name')->toArray();
    }
}
