<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UserService
{
    public function getProfileByUsername(string $username)
    {
        // Busca o usuário pelo username. Se não achar, retorna erro 404 automaticamente.
        return User::where('username', $username)->firstOrFail();
    }

    public function updateProfile(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function uploadAvatar(User $user, UploadedFile $file)
    {
        // Se o usuário já tiver um avatar (e não for um link HTTP externo de teste), deletamos o antigo do disco
        if ($user->avatar_url && !str_starts_with($user->avatar_url, 'http')) {
            // Ajustamos o caminho de deleção para funcionar com a nova URL
            $oldPath = str_replace('/storage/', '', $user->avatar_url);
            Storage::disk('public')->delete($oldPath);
        }

        // Salva a nova imagem na pasta storage/app/public/avatars
        // Isso retorna algo como 'avatars/nome-da-imagem.jpg'
        $path = $file->store('avatars', 'public');
        
        // Montamos a URL oficial apontando para o atalho do storage
        $avatarUrl = '/storage/' . $path;
        
        // Atualiza o banco de dados com a nova URL
        $user->update(['avatar_url' => $avatarUrl]);

        return $user;
    }

    public function searchUsers(string $query)
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->limit(15)
            ->get();
    }

    public function getSuggestions(User $user)
    {
        // Retorna perfis aleatórios sugeridos, excluindo o próprio usuário logado
        return User::where('id', '!=', $user->id)
            ->inRandomOrder()
            ->limit(5)
            ->get();
    }
}