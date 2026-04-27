<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importação limpa de todos os Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;

// --------------------------------------------------------
// Rotas de Autenticação (Task 2)
// --------------------------------------------------------
Route::prefix('auth')->group(function () {
    // Rotas Públicas
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Rotas Protegidas (Exigem Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

// --------------------------------------------------------
// TODAS AS ROTAS PROTEGIDAS PELO SISTEMA (Tasks 3, 4, etc)
// --------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Retorna o próprio usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --------------------------------------------------------
    // Usuários, Perfil e Follow (Task 3)
    // --------------------------------------------------------
    Route::prefix('users')->group(function () {
        
        // 1º Rotas Estáticas (Buscas e Configurações)
        Route::get('/search', [UserController::class, 'search']);
        Route::get('/suggestions', [UserController::class, 'suggestions']);
        Route::put('/me', [UserController::class, 'update']);
        Route::post('/me/avatar', [UserController::class, 'uploadAvatar']);

        // 2º Rotas de Follow
        Route::post('/{id}/follow', [FollowController::class, 'follow']);
        Route::delete('/{id}/follow', [FollowController::class, 'unfollow']);
        Route::get('/{id}/followers', [FollowController::class, 'followers']);
        Route::get('/{id}/following', [FollowController::class, 'following']);
        Route::get('/{id}/is-following', [FollowController::class, 'checkFollow']);

        // 3º Rota Dinâmica Curinga (Sempre por último nesta seção)
        Route::get('/{username}', [UserController::class, 'show']);
    });

    // --------------------------------------------------------
    // Feed e Posts (Task 4)
    // --------------------------------------------------------
    Route::get('/feed', [PostController::class, 'index']);
    
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::post('/{id}/like', [PostController::class, 'toggleLike']);

        // Comentários ligados a um Post específico
        Route::get('/{postId}/comments', [CommentController::class, 'index']);
        Route::post('/{postId}/comments', [CommentController::class, 'store']);
    });

    // --------------------------------------------------------
    // Ações diretas nos Comentários
    // --------------------------------------------------------
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

});

// --------------------------------------------------------
// ARQUIVOS PÚBLICOS (Não usar Sanctum aqui)
// --------------------------------------------------------

// Rota para carregar imagens do Avatar
Route::get('/avatars/{filename}', function ($filename) {
    $path = storage_path('app/public/avatars/' . $filename);
    
    if (!file_exists($path)) {
        return response()->json(['error' => 'Imagem não encontrada.'], 404);
    }
    
    return response()->file($path);
});

// Rota de resgate para as imagens dos Posts
Route::get('/images/{filename}', function ($filename) {
    $path = storage_path('app/public/posts/' . $filename);
    
    if (!file_exists($path)) {
        return response()->json(['error' => 'Imagem não encontrada.'], 404);
    }
    
    return response()->file($path);
});

Route::prefix('users')->group(function () {
        
    // 1º Rotas Estáticas (Buscas e Configurações)
    Route::get('/search', [UserController::class, 'search']);
    Route::get('/suggestions', [UserController::class, 'suggestions']);
    Route::put('/me', [UserController::class, 'update']);
    Route::post('/me/avatar', [UserController::class, 'uploadAvatar']);

    // 2º Rotas de Follow
    Route::post('/{id}/follow', [FollowController::class, 'follow']);
    Route::delete('/{id}/follow', [FollowController::class, 'unfollow']);
    Route::get('/{id}/followers', [FollowController::class, 'followers']);
    Route::get('/{id}/following', [FollowController::class, 'following']);
    Route::get('/{id}/is-following', [FollowController::class, 'checkFollow']);

    // 3º Rota dos Posts do Usuário (NOVA)
    Route::get('/{id}/posts', [PostController::class, 'userPosts']);

    // 4º Rota Dinâmica Curinga (Sempre por último nesta seção)
    Route::get('/{username}', [UserController::class, 'show']);
});