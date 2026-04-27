<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    // GET /api/feed
    public function index()
    {
        $posts = $this->postService->getFeed();
        return response()->json($posts);
    }

    // POST /api/posts
    public function store(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // até 5MB
            'description' => 'nullable|string|max:1000',
        ]);

        $post = $this->postService->createPost(
            $request->user(),
            $request->only('description'),
            $request->file('image')
        );

        // Retorna o post recém-criado já com os dados do usuário atrelados
        $post->load('user');

        return response()->json($post, 201);
    }

    // POST /api/posts/{id}/like
    public function toggleLike(Request $request, $id)
    {
        $result = $this->postService->toggleLike($request->user(), $id);
        return response()->json($result);
    }

    // Mostrar um post específico
    public function show($id)
    {
        // Busca o post e já traz as informações do autor do post
        $post = \App\Models\Post::with('user:id,name,username')->findOrFail($id);

        return response()->json($post);
    }
}