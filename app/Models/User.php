<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    public const ROLE_USER = 'user';
    public const ROLE_MODERATOR = 'moderator';

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
