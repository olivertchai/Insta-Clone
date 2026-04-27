<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    protected FollowService $followService;
    // Injetamos o Service no construtor
    public function __construct(FollowService $followService)
    {
        $this->followService = $followService;
    }

    // Ação de Seguir (POST)
    public function follow(Request $request, $id)
    {
        // Verifica se o usuário existe para dar 404 caso não exista
        User::findOrFail($id);
        
        $this->followService->follow($request->user(), $id);

        return response()->json(['message' => 'Usuário seguido com sucesso.']);
    }

    // Ação de Desseguir (DELETE)
    public function unfollow(Request $request, $id)
    {
        User::findOrFail($id);
        
        $this->followService->unfollow($request->user(), $id);

        return response()->json(['message' => 'Você deixou de seguir o usuário.']);
    }

    // Listar seguidores de um usuário
    public function followers($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user->followers()->select('users.id', 'name', 'username')->paginate(15));
    }

    // Listar quem um usuário está seguindo
    public function following($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user->following()->select('users.id', 'name', 'username')->paginate(15));
    }

    // Verificar se o usuário logado segue um perfil específico
    public function checkFollow(Request $request, $id)
    {
        $isFollowing = $request->user()->following()->where('following_id', $id)->exists();
        return response()->json(['is_following' => $isFollowing]);
    }
}