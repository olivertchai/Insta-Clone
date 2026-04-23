<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Registra um novo usuário e já retorna o token de acesso.
     */
    public function register(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            // Já prevendo o 'username' que está na Task 3 da sua doc:
            'username' => $data['username'], 
            'password' => Hash::make($data['password']),
        ]);

        // Cria o token via Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Valida as credenciais e retorna o usuário com o token de acesso.
     */
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        // Verifica se o usuário existe e se a senha está correta
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Revoga os tokens antigos por segurança (opcional, mas recomendado para o InstaClone)
        $user->tokens()->delete();

        // Cria um novo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Revoga o token atual do usuário autenticado.
     */
    public function logout(User $user)
    {
        $user->currentAccessToken()->delete();
        
        return true;
    }
}