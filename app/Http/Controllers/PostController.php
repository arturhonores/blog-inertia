<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest; // Importamos el Form Request
use App\Models\Post;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // para dashboard

//para depuración
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    // Mostrar lista de posts
    public function index(Request $request)
    {
        // Obtener los filtros de la request
        $categories = $request->input('categories', []);
        $search = $request->input('search', '');

        // Asegurarnos de que $categories sea siempre un array
        if (!is_array($categories)) {
            $categories = explode(',', $categories);
        }

        // Construimos la consulta base para los posts
        $query = Post::query();

        // Si hay categorías seleccionadas, filtramos por esas categorías
        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        // Si hay una búsqueda por título, filtramos por el título
        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Obtenemos los posts filtrados ordenados por fecha de publicación y aplicamos la paginación
        $posts = $query->orderBy('publish_date', 'desc')
            ->paginate(9)
            ->appends([
                'search' => $search,
                'categories' => $categories,
            ]);

        // Retornamos la vista con los posts y las categorías seleccionadas
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'filters' => [
                'categories' => $categories,
                'search' => $search,
            ],
        ]);
    }

    // Mostrar formulario de creación de un nuevo post
    public function create()
    {
        // Obtenemos las categorías,autores y tags para pasarlos al formulario
        $categories = Category::all();
        $authors = Author::all();
        $tags = Tag::all();

        return Inertia::render('Posts/Create', [
            'categories' => $categories,
            'authors' => $authors,
            'tags' => $tags,
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
        $validatedData['image_post_url'] = $this->uploadImage($request, 'image_post_url', 'posts',  $validatedData['slug']);
        $validatedData['image_card_url'] = $this->uploadImage($request, 'image_card_url', 'cards',  $validatedData['slug']);

        // Crear el post con los datos validados
        // Post::create($validatedData); (sin tags)
        $post = Post::create($validatedData);


        // Sincronizar tags
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('posts.index')->with('success', 'Post creado con éxito');
    }

    // Mostrar los detalles de un post específico
    public function show(Post $post)
    {
        // Cargar los tags asociados al post
        $post->load('tags');
        return Inertia::render('Posts/Show', ['post' => $post]);
    }

    // Mostrar formulario para editar un post existente
    public function edit(Post $post)
    {
        // Obtenemos las categorías, autores y tags para pasarlos al formulario de edición
        $categories = Category::all();
        $authors = Author::all();
        $tags = Tag::all();

        // Cargar el post con sus tags
        $post->load('tags');

        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'categories' => $categories,
            'authors' => $authors,
            'tags' => $tags, // Pasamos los tags al frontend
        ]);
    }

    public function update(PostRequest $request, Post $post)
    {
        // Log::info('Datos recibidos para actualizar el post:', $request->all());

        // Validación de los datos
        $validatedData = $request->validated();

        // Asignar el user_id del usuario autenticado
        $validatedData['user_id'] = Auth::id();

        // Manejar las imágenes
        //imagen del post
        if ($request->hasFile('image_post_url')) {

            // Subir la nueva imagen reemplazando la anterior ya que poseen el mismo slug
            $validatedData['image_post_url'] = $this->uploadImage($request, 'image_post_url', 'posts', $validatedData['slug']);
        } else {
            // Mantener la imagen existente si no se sube una nueva
            unset($validatedData['image_post_url']);
        }

        //imagen de la card
        if ($request->hasFile('image_card_url')) {

            // Subir la nueva imagen reemplazando la anterior ya que poseen el mismo slug
            $validatedData['image_card_url'] = $this->uploadImage($request, 'image_card_url', 'cards', $validatedData['slug']);
        } else {
            // Mantener la imagen existente si no se sube una nueva
            unset($validatedData['image_card_url']);
        }

        // Actualizar el post con los datos validados
        $post->update($validatedData);

        // Sincronizar los tags (si se envían desde el frontend)
        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags', []));
        }

        return redirect()->route('posts.index')->with('success', 'Post actualizado con éxito');
    }

    // Eliminar un post específico de la base de datos
    public function destroy(Post $post)
    {
        // Verificar si existen imágenes asociadas con el post
        if ($post->image_post_url) {
            // Extraer la clave del archivo desde la URL de S3
            $imagePostPath = parse_url($post->image_post_url, PHP_URL_PATH); // Obtener la ruta
            $imagePostPath = ltrim($imagePostPath, '/'); // Remover la barra inicial si la hay

            // Eliminar el prefijo 'blogfap/' si está presente
            // $imagePostPath = str_replace('blogfap/', '', $imagePostPath);
            $imagePostPath = str_replace('blog-fap/', '', $imagePostPath);

            // Debug: Verifica el path que estás intentando eliminar
            // Log::info('Intentando eliminar la imagen del post en S3 con el path corregido: ' . $imagePostPath);

            // Eliminar la imagen del post de S3
            Storage::disk('s3')->delete($imagePostPath);
        }

        if ($post->image_card_url) {
            // Extraer la clave del archivo desde la URL de S3
            $imageCardPath = parse_url($post->image_card_url, PHP_URL_PATH); // Obtener la ruta
            $imageCardPath = ltrim($imageCardPath, '/'); // Remover la barra inicial si la hay

            // Eliminar el prefijo 'blogfap/' si está presente
            // $imageCardPath = str_replace('blogfap/', '', $imageCardPath);
            $imageCardPath = str_replace('blog-fap/', '', $imageCardPath);

            // Debug: Verifica el path que estás intentando eliminar
            // Log::info('Intentando eliminar la imagen de la card en S3 con el path corregido: ' . $imageCardPath);

            // Eliminar la imagen de la card de S3
            Storage::disk('s3')->delete($imageCardPath);
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post eliminado con éxito');
    }

    // Función privada para manejar la subida de imágenes a S3
    private function uploadImage($request, $inputName, $folder, $slug)
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            // Usar el slug del post como nombre del archivo, manteniendo la extensión original
            $fileName = $slug . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->putFileAs($folder, $file, $fileName); // Subir el archivo con el nombre del slug
            return Storage::disk('s3')->url($path); // Retorna la URL pública de la imagen
        }

        return null;
    }

    public function dashboard()
    {
        $postsByYear = DB::table('posts')
            ->selectRaw('YEAR(publish_date) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        // Nueva consulta: obtener posts de 2024 agrupados por categoría
        $postsByCategory2024 = DB::table('posts')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, COUNT(posts.id) as count')
            ->whereYear('publish_date', 2024)
            ->groupBy('category')
            ->get();

        // Nueva consulta: obtener la cantidad de posts por tag solo del año 2024
        $postsByTag2024 = DB::table('tags')
            ->join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
            ->join('posts', 'posts.id', '=', 'post_tag.post_id')
            ->selectRaw('tags.name as tag, COUNT(post_tag.post_id) as count')
            ->whereYear('posts.publish_date', 2024) // Filtra por el año 2024
            ->groupBy('tags.name')
            ->orderByDesc('count')
            ->get();

        return Inertia::render('Dashboard', [
            'postsByYear' => $postsByYear,
            'postsByCategory2024' => $postsByCategory2024,
            'postsByTag2024' => $postsByTag2024
        ]);
    }
}
