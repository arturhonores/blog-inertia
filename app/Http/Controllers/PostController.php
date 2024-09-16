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
            'meta_title' => 'required|max:100',
            'meta_description' => 'required|max:200',
            'image_post_url' => 'required|url',
            'image_card_url' => 'required|url',
            'post_html' => 'required',
            'summary' => 'required|max:180',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.unique' => 'El slug ya está en uso. Por favor, elige otro.',
            'meta_title.required' => 'El meta título es obligatorio.',
            'meta_title.max' => 'El meta título no puede tener más de 100 caracteres.',
            'meta_description.required' => 'La meta descripción es obligatoria.',
            'meta_description.max' => 'La meta descripción no puede tener más de 200 caracteres.',
            'image_post_url.required' => 'La URL de la imagen del post es obligatoria.',
            'image_post_url.url' => 'La URL de la imagen del post debe ser una URL válida.',
            'image_card_url.required' => 'La URL de la imagen de la tarjeta es obligatoria.',
            'image_card_url.url' => 'La URL de la imagen de la tarjeta debe ser una URL válida.',
            'post_html.required' => 'El contenido del post es obligatorio.',
            'summary.required' => 'El resumen es obligatorio.',
            'summary.max' => 'El resumen no puede tener más de 180 caracteres.',
            'publish_date.required' => 'La fecha de publicación es obligatoria.',
            'publish_date.date' => 'La fecha de publicación debe tener un formato válido.',
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no es válido.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
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
        // Obtenemos las categorías y autores para pasarlos al formulario de edición
        $categories = Category::all();
        $authors = Author::all();

        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }

    // Actualizar un post existente en la base de datos
    public function update(Request $request, Post $post)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'meta_title' => 'required|max:100',
            'meta_description' => 'required|max:200',
            'image_post_url' => 'required|url',
            'image_card_url' => 'required|url',
            'post_html' => 'required',
            'summary' => 'required|max:180',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.unique' => 'El slug ya está en uso. Por favor, elige otro.',
            'meta_title.required' => 'El meta título es obligatorio.',
            'meta_title.max' => 'El meta título no puede tener más de 100 caracteres.',
            'meta_description.required' => 'La meta descripción es obligatoria.',
            'meta_description.max' => 'La meta descripción no puede tener más de 200 caracteres.',
            'image_post_url.required' => 'La URL de la imagen del post es obligatoria.',
            'image_post_url.url' => 'La URL de la imagen del post debe ser una URL válida.',
            'image_card_url.required' => 'La URL de la imagen de la tarjeta es obligatoria.',
            'image_card_url.url' => 'La URL de la imagen de la tarjeta debe ser una URL válida.',
            'post_html.required' => 'El contenido del post es obligatorio.',
            'summary.required' => 'El resumen es obligatorio.',
            'summary.max' => 'El resumen no puede tener más de 180 caracteres.',
            'publish_date.required' => 'La fecha de publicación es obligatoria.',
            'publish_date.date' => 'La fecha de publicación debe tener un formato válido.',
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no es válido.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
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
