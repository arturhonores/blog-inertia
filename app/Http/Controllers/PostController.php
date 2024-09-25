<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest; // Importamos el Form Request
use App\Models\Post;
use App\Models\Author;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//para depuración
use Illuminate\Support\Facades\Log;

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
    public function store(PostRequest $request)
    {
        //depuración
        // Log::info('Datos recibidos para crear el post:', $request->all());

        // Validación de los datos
        $validatedData = $request->validated();

        // Asignar el user_id del usuario autenticado
        $validatedData['user_id'] = Auth::id();

        // Manejar las imágenes
        //image_post_url y image_card_url NO SON URL, SON ARCHIVOS, cambio de nombre de variable NECESARIO para próxima versión
        $validatedData['image_post_url'] = $this->uploadImage($request, 'image_post_url', 'posts');
        $validatedData['image_card_url'] = $this->uploadImage($request, 'image_card_url', 'cards');

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
    public function update(PostRequest $request, Post $post)
    {

        // Log::info('Datos recibidos para actualizar el post:', $request->all());

        // Validación de los datos
        $validatedData = $request->validated();

        // Asignar el user_id del usuario autenticado
        $validatedData['user_id'] = Auth::id();

        // Manejar las imágenes
        if ($request->hasFile('image_post_url')) {
            $validatedData['image_post_url'] = $this->uploadImage($request, 'image_post_url', 'posts');
        } else {
            // Mantener la imagen existente si no se sube una nueva
            unset($validatedData['image_post_url']);
        }

        if ($request->hasFile('image_card_url')) {
            $validatedData['image_card_url'] = $this->uploadImage($request, 'image_card_url', 'cards');
        } else {
            // Mantener la imagen existente si no se sube una nueva
            unset($validatedData['image_card_url']);
        }

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

    // Función privada para manejar la subida de imágenes a S3
    private function uploadImage($request, $inputName, $folder)
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $path = Storage::disk('s3')->put($folder, $file); // Almacenar la imagen en la carpeta 'cards' o "post" de S3
            return Storage::disk('s3')->url($path); // Retorna la URL pública de la imagen
        }

        return null;
    }
}
