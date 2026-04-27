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
        'username', // Adicionado
        'email',
        'password',
        'bio',        // Adicionado
        'avatar_url', // Adicionado
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
     * Um usuário tem UM perfil.
     */
    public function profile(){
        return $this->hasOne(Profile::class);
    }

    /**
     * Um usuário tem MUITOS posts.
     */
    public function posts()
    {
        // Retorna os posts do usuário, já ordenados do mais novo pro mais velho
        return $this->hasMany(Post::class)->latest();
    }

    // Os posts que este usuário curtiu
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Pessoas que este usuário está seguindo
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    // Pessoas que seguem este usuário (Seguidores)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }
}
