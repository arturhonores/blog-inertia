<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Author;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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
        // Obtenemos las categorías y autores para pasarlos al formulario
        $categories = Category::all();
        $authors = Author::all();

        return Inertia::render('Posts/Create', [
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }

    // Guardar un nuevo post en la base de datos
    public function store(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug',
            'meta_title' => 'nullable|max:100',
            'meta_description' => 'nullable|max:200',
            'image_post_url' => 'nullable|url',
            'image_card_url' => 'nullable|url',
            'post_html' => 'required',
            'summary' => 'nullable|max:250',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Asignar el user_id del usuario autenticado
        $validatedData['user_id'] = Auth::id();

        // Crear el post con los datos validados
        Post::create($validatedData);

        return redirect()->route('posts.index')->with('success', 'Post creado con éxito');
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
        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'meta_title' => 'nullable|max:100',
            'meta_description' => 'nullable|max:200',
            'image_post_url' => 'nullable|url',
            'image_card_url' => 'nullable|url',
            'post_html' => 'required',
            'summary' => 'nullable|max:250',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Actualizar el post con los datos validados
        $post->update($validatedData);

        return redirect()->route('posts.index')->with('success', 'Post actualizado con éxito');
    }

    // Eliminar un post específico de la base de datos
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post eliminado con éxito');
    }
}
