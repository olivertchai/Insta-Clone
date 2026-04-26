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
        return $this->hasMany(Post::class);
    }
}
