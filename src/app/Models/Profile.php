<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    // Proteção contra Mass Assignment
    protected $fillable = [
        'user_id',
        'username',
        'bio',
        'avatar_path',
    ];

    /**
     * O perfil PERTENCE A um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
