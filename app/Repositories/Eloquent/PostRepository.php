<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        // O "with('user.profile')" é crucial aqui! 
        // Ele faz o Eager Loading, evitando o problema de N+1 queries.
        // O "latest()" ordena dos mais recentes para os mais antigos.
        return Post::with('user.profile')->latest()->paginate($perPage);
    }
}