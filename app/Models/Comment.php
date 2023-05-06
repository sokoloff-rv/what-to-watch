<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read User $user
 */
class Comment extends Model
{
    use HasFactory;

    public const ANONYMOUS_NAME = 'Гость';

    protected $fillable = [
        'user_id',
        'film_id',
        'comment_id',
        'text',
        'rating',
        'is_external',
    ];

    protected $appends = [
        'author_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    public function doesNotHaveChildren()
    {
        return $this->children()->count() === 0;
    }

    protected function getAuthorNameAttribute()
    {
        if ($this->is_external) {
            return $this::ANONYMOUS_NAME;
        }
        return $this->user->name;
    }
}
