<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class PostService
{
    public function getFeed()
    {
        // Traz os posts com os dados do dono, ordenados do mais novo pro mais velho.
        // Usamos paginate(10) porque o frontend espera carregar 10 posts por vez!
        return Post::with('user')->latest()->paginate(10);
    }

    public function createPost(User $user, array $data, UploadedFile $file)
    {
        // 1. Cria um nome único para o arquivo
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // 2. SALVA O ARQUIVO FISICAMENTE na pasta storage/app/public/posts
        $file->storeAs('posts', $filename, 'public');

        // 3. Monta a URL pública que o frontend vai usar para exibir a imagem
        $imageUrl = asset('storage/posts/' . $filename);
        
        // 4. Salva o registro no banco de dados
        return $user->posts()->create([
            'description' => $data['description'] ?? null,
            'imageUrl'  => $imageUrl,
        ]);
    }

    public function toggleLike(User $user, $postId)
    {
        // Busca o post ou dá erro 404 se não existir
        $post = Post::findOrFail($postId);
        
        // O "toggle" liga ou desliga a curtida magicamente
        $user->likedPosts()->toggle($post->id);

        // Retorna o novo total de curtidas para o frontend atualizar a tela
        return [
            'message' => 'Ação realizada com sucesso',
            'likes_count' => $post->likes()->count(),
            // Verifica se o usuário atual curte esse post
            'is_liked' => $user->likedPosts()->where('post_id', $postId)->exists(),
        ];
    }
}