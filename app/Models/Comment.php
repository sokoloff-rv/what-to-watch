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

    public const ANONYMOUS_NAME = 'Анонимный пользователь';

    protected $fillable = [
        'user_id',
        'film_id',
        'parent_id',
        'text',
        'rating',
        'is_external',
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
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function doesNotHaveChildren()
    {
        return $this->children()->count() === 0;
    }

    public function getAuthorName()
    {
        if ($this->is_external) {
            return $this::ANONYMOUS_NAME;
        }
        return $this->user->name;
    }
}
