<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_MODERATOR = 'moderator';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function favoriteFilms()
    {
        return $this->belongsToMany(Film::class, 'user_favorites');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
