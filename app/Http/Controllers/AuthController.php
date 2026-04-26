<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Injetamos o AuthService diretamente pelo construtor (recurso do PHP 8+)
    public function __construct(private AuthService $authService)
    {
    }

    public function register(Request $request)
    {
        // 1. Valida a requisição
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Adicione 'confirmed' se o front for mandar password_confirmation
        ]);

        // 2. Repassa para o Service
        $data = $this->authService->register($validated);

        // 3. Retorna a resposta
        return response()->json($data, 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $data = $this->authService->login($validated);

        return response()->json($data, 200);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logout realizado com sucesso.'], 200);
    }

    public function me(Request $request)
    {
        // Retorna os dados do usuário logado
        return response()->json($request->user(), 200);
    }

    public function refresh(Request $request)
    {
        // No Sanctum, a "renovação" geralmente consiste em apagar o token atual e emitir um novo
        $user = $request->user();
        $user->currentAccessToken()->delete();
        
        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $newToken,
        ], 200);
    }
}