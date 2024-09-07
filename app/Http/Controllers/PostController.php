<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    // Mostrar lista de posts
    public function index()
    {
        // Obtenemos los primeros 10 posts ordenados por fecha de publicación
        $posts = Post::orderBy('publish_date', 'desc')->get();
        //inicial
        return Inertia::render('Posts/Index', ['posts' => $posts]);
    }

    // Mostrar formulario de creación de un nuevo post
    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    // Guardar un nuevo post en la base de datos
    public function store(Request $request)
    {
        Post::create($request->all());
        return redirect()->route('posts.index');
    }

    // Mostrar los detalles de un post específico
    public function show(Post $post)
    {
        return Inertia::render('Posts/Show', ['post' => $post]);
    }

    // Mostrar formulario para editar un post existente
    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', ['post' => $post]);
    }

    // Actualizar un post existente en la base de datos
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());
        return redirect()->route('posts.index');
    }

    // Eliminar un post específico de la base de datos
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index');
    }
}
