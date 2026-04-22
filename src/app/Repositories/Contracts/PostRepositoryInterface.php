<?php

namespace App\Repositories\Contracts;

interface PostRepositoryInterface
{
    /**
     * Retorna os posts paginados.
     */
    public function getAllPaginated(int $perPage = 10);
}