<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Certifique-se que esta linha existe

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'bio',
        'avatar_url',
        'password',
    ];

    /**
     * Os atributos que devem ser escondidos em arrays (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos (cast).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Sempre expõe avatar_url como URL absoluta na API.
     */
    public function getAvatarUrlAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return url($value);
    }
    
    /**
     * Um usuário tem MUITOS posts.
     */
    public function posts()
    {
        // Retorna os posts do usuário, já ordenados do mais novo pro mais velho
        return $this->hasMany(Post::class)->latest();
    }

    /**
     * Um usuário tem MUITOS posts curtidos.
     */
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    /**
     * Um usuário tem MUITOS comentários.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Um usuário tem MUITOS seguidores.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Um usuário tem MUITOS seguidos.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }
}
