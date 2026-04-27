<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    // Listar comentários de um post (Paginado)
    public function index($postId)
    {
        $post = Post::findOrFail($postId);
        
        // Retorna os comentários com os dados do usuário que comentou, em ordem do mais recente
        $comments = $post->comments()->with('user:id,name,username')->latest()->paginate(15);
        
        return response()->json($comments);
    }

    // Criar um comentário
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Comentário adicionado com sucesso',
            'comment' => $comment->load('user:id,name,username')
        ], 201);
    }

    // Atualizar um comentário (Protegido pela Policy)
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
        'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Comentário atualizado',
            'comment' => $comment
        ]);
    }

    // Deletar um comentário (Protegido pela Policy)
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comentário deletado com sucesso']);
    }
}