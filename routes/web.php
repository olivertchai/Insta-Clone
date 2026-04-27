<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', function () {
    return view('swagger');
});

Route::get('/swagger', function () {
    return redirect('/docs');
});

Route::get('/api/docs', function () {
    return view('swagger');
});

// Rota para forçar a exibição da imagem do avatar ignorando o link simbólico
Route::get('/storage/avatars/{filename}', function ($filename) {
    $path = storage_path('app/public/avatars/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
});