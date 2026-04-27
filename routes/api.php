<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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
// Rotas de Usuário / Perfil (Task 3)
// --------------------------------------------------------
Route::prefix('users')->group(function () {
    
    // Rotas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/search', [UserController::class, 'search']);
        Route::get('/suggestions', [UserController::class, 'suggestions']);
        Route::put('/me', [UserController::class, 'update']);
        Route::post('/me/avatar', [UserController::class, 'uploadAvatar']);
    });

    // A rota curinga com parâmetro dinâmico ({username}) deve sempre ficar por último
    // para não capturar acidentalmente as rotas como "search" ou "me"
    Route::get('/{username}', [UserController::class, 'show'])->middleware('auth:sanctum');
});

// --------------------------------------------------------
// Feed e Posts (Task 4) e Outras Rotas
// --------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Feed e Posts
    Route::get('/feed', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    
    // Curtidas
    Route::post('/posts/{id}/like', [PostController::class, 'toggleLike']);


    // Rotas de Comentários
    Route::get('/posts/{postId}/comments', [\App\Http\Controllers\CommentController::class, 'index']);
    Route::post('/posts/{postId}/comments', [\App\Http\Controllers\CommentController::class, 'store']);
    Route::put('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy']);
    
    // Rota para buscar um único post
    Route::get('/posts/{id}', [\App\Http\Controllers\PostController::class, 'show']);

    // Rotas de Follow
    Route::post('/users/{id}/follow', [\App\Http\Controllers\FollowController::class, 'follow']);
    Route::delete('/users/{id}/follow', [\App\Http\Controllers\FollowController::class, 'unfollow']);
    Route::get('/users/{id}/followers', [\App\Http\Controllers\FollowController::class, 'followers']);
    Route::get('/users/{id}/following', [\App\Http\Controllers\FollowController::class, 'following']);
    Route::get('/users/{id}/is-following', [\App\Http\Controllers\FollowController::class, 'checkFollow']);
    
});

// --------------------------------------------------------
// ARQUIVOS PÚBLICOS (Não usar Sanctum aqui)
// --------------------------------------------------------

// Rota infalível para carregar imagens do Avatar ignorando os atalhos do Docker
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