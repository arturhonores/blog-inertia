<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        //depuración
        Log::info('Datos recibidos para crear el post:', $request->all());

        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug',
            'meta_title' => 'required|max:100',
            'meta_description' => 'required|max:200',
            'image_post_url' => 'required|file|mimes:jpg,jpeg,png,gif,webp',  // Cambiar a validación de archivo
            'image_card_url' => 'required|file|mimes:jpg,jpeg,png,gif,webp',  // Cambiar a validación de archivo
            'post_html' => 'required',
            'summary' => 'required|max:700',
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
            'image_post_url.required' => 'La imagen del post es obligatoria.',  // Cambiar mensaje de error
            'image_post_url.file' => 'Debes subir un archivo válido para la imagen del post.', // Validar que sea un archivo
            'image_post_url.mimes' => 'La imagen del post debe ser un archivo JPG, PNG, WEBP o GIF.', // Validar formato del archivo
            'image_card_url.required' => 'La imagen de la tarjeta es obligatoria.',
            'image_card_url.file' => 'Debes subir un archivo válido para la imagen de la tarjeta.',
            'image_card_url.mimes' => 'La imagen de la tarjeta debe ser un archivo JPG, PNG, WEBP o GIF.',
            'post_html.required' => 'El contenido del post es obligatorio.',
            'summary.required' => 'El resumen es obligatorio.',
            'summary.max' => 'El resumen no puede tener más de 700 caracteres.',
            'publish_date.required' => 'La fecha de publicación es obligatoria.',
            'publish_date.date' => 'La fecha de publicación debe tener un formato válido.',
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no es válido.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
        ]);

        // Asignar el user_id del usuario autenticado
        $validatedData['user_id'] = Auth::id();

        // Subir imagen del post a S3
        if ($request->hasFile('image_post_url')) {
            $file = $request->file('image_post_url');
            // Almacenar la imagen en la carpeta 'posts'
            $route = Storage::disk('s3')->put('posts', $file);
            // Generar URL pública
            $url = Storage::disk('s3')->url($route);
            $validatedData['image_post_url'] = $url; // Guardar la URL en la base de datos
        }

        // Subir imagen de la tarjeta a S3
        if ($request->hasFile('image_card_url')) {
            $file = $request->file('image_card_url');
            $route = Storage::disk('s3')->put('cards', $file); // Almacenar la imagen en la carpeta 'cards'
            $url = Storage::disk('s3')->url($route); // Generar URL pública
            $validatedData['image_card_url'] = $url; // Guardar la URL en la base de datos
        }

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

        Log::info('Datos recibidos para actualizar el post:', $request->all());

        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'meta_title' => 'required|max:100',
            'meta_description' => 'required|max:200',
            'image_post_url' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp', // Validar la imagen si es proporcionada
            'image_card_url' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp', // Validar la imagen si es proporcionada
            'post_html' => 'required',
            'summary' => 'required|max:700',
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
            'image_post_url.required' => 'La imagen del post es obligatoria.',  // Cambiar mensaje de error
            'image_post_url.file' => 'Debes subir un archivo válido para la imagen del post.', // Validar que sea un archivo
            'image_post_url.mimes' => 'La imagen del post debe ser un archivo JPG, PNG, WEBP o GIF.', // Validar formato del archivo
            'image_card_url.required' => 'La imagen de la tarjeta es obligatoria.',
            'image_card_url.file' => 'Debes subir un archivo válido para la imagen de la tarjeta.',
            'image_card_url.mimes' => 'La imagen de la tarjeta debe ser un archivo JPG, PNG, WEBP o GIF.',
            'post_html.required' => 'El contenido del post es obligatorio.',
            'summary.required' => 'El resumen es obligatorio.',
            'summary.max' => 'El resumen no puede tener más de 700 caracteres.',
            'publish_date.required' => 'La fecha de publicación es obligatoria.',
            'publish_date.date' => 'La fecha de publicación debe tener un formato válido.',
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no es válido.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
        ]);

        // Asignar el user_id del usuario autenticado
        $validatedData['user_id'] = Auth::id();

        // Manejar la imagen del post
        if ($request->hasFile('image_post_url')) {
            $file = $request->file('image_post_url');
            // Almacenar la imagen en la carpeta 'posts' de S3
            $route = Storage::disk('s3')->put('posts', $file);
            // Obtener la URL pública de la imagen
            $url = Storage::disk('s3')->url($route);
            $validatedData['image_post_url'] = $url;
        } else {
            // Mantener la imagen existente si no se sube una nueva
            unset($validatedData['image_post_url']);
        }

        // Manejar la imagen de la tarjeta
        if ($request->hasFile('image_card_url')) {
            $file = $request->file('image_card_url');
            // Almacenar la imagen en la carpeta 'cards' de S3
            $route = Storage::disk('s3')->put('cards', $file);
            // Obtener la URL pública de la imagen
            $url = Storage::disk('s3')->url($route);
            $validatedData['image_card_url'] = $url;
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
}
