<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // Importar la facade Auth

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Permitir la autorización
        return Auth::check(); // Solo los usuarios autenticados tienen permiso.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $postId = $this->post ? $this->post->id : null;

        return [
            // El título debe ser único (excluyendo el propio post)
            'title' => 'required|max:255|unique:posts,title,' . $postId,
            // El slug debe ser único (excluyendo el propio post)
            'slug' => 'required|unique:posts,slug,' . $postId,
            'meta_title' => 'required|max:100',
            'meta_description' => 'required|max:200',
            'image_post_url' => $this->isMethod('post') ? 'required|file|mimes:jpg,jpeg,png,gif,webp' : 'nullable|file|mimes:jpg,jpeg,png,gif,webp',
            'image_card_url' => $this->isMethod('post') ? 'required|file|mimes:jpg,jpeg,png,gif,webp' : 'nullable|file|mimes:jpg,jpeg,png,gif,webp',
            'post_html' => 'required',
            'summary' => 'required|max:700',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',  // Marcar como opcional
            'tags.*' => 'nullable|exists:tags,id',  // Asegurarse de que los tags existan si se seleccionan
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'title.unique' => 'El título ya está en uso. Por favor, elige otro.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.unique' => 'El slug ya está en uso. Por favor, elige otro.',
            'meta_title.required' => 'El meta título es obligatorio.',
            'meta_title.max' => 'El meta título no puede tener más de 100 caracteres.',
            'meta_description.required' => 'La meta descripción es obligatoria.',
            'meta_description.max' => 'La meta descripción no puede tener más de 200 caracteres.',
            'image_post_url.required' => 'La imagen del post es obligatoria.',
            'image_post_url.file' => 'Debes subir un archivo válido para la imagen del post.',
            'image_post_url.mimes' => 'La imagen del post debe ser un archivo JPG, PNG, WEBP o GIF.',
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
        ];
    }
}
