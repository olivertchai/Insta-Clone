<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    // GET /api/users/{username}
    public function show($username)
    {
        $user = $this->userService->getProfileByUsername($username);
        return response()->json($user);
    }

    // PUT /api/users/me
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            // O username deve ser único na tabela users, mas ignorando o ID do próprio usuário atual
            'username' => 'sometimes|string|max:30|regex:/^[A-Za-z0-9._]+$/|unique:users,username,' . $request->user()->id,
            'bio'      => 'nullable|string|max:500',
        ]);

        $user = $this->userService->updateProfile($request->user(), $validated);

        return response()->json($user);
    }

    // POST /api/users/me/avatar
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // max 2MB conforme documentação
        ]);

        $user = $this->userService->uploadAvatar($request->user(), $request->file('avatar'));

        return response()->json($user);
    }

    // GET /api/users/search?q=
    public function search(Request $request)
    {
        $query = $request->query('q', '');
        $users = $this->userService->searchUsers($query);
        return response()->json($users);
    }

    // GET /api/users/suggestions
    public function suggestions(Request $request)
    {
        $users = $this->userService->getSuggestions($request->user());
        return response()->json($users);
    }
}