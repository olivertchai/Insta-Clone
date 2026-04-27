<?php

namespace App\Services;

use App\Exceptions\SelfFollowException;
use App\Models\User;

class FollowService
{
    public function follow(User $user, $targetUserId)
    {
        if ($user->id == $targetUserId) {
            throw new SelfFollowException();
        }

        // Adiciona a pessoa, mas ignora se já estiver seguindo
        $user->following()->syncWithoutDetaching([$targetUserId]);
    }

    public function unfollow(User $user, $targetUserId)
    {
        if ($user->id == $targetUserId) {
            throw new SelfFollowException();
        }

        // Remove a pessoa
        $user->following()->detach($targetUserId);
    }
}