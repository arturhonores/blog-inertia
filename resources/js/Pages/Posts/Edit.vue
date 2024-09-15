<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, watch } from 'vue';
import slugify from 'slugify';

const props = defineProps({
    authors: Array,
    categories: Array,
    post: Object, // Recibimos el post a editar
});

// Inicializamos el formulario con los datos existentes del post
const form = useForm({
    title: props.post.title || '',
    slug: props.post.slug || '',
    meta_title: props.post.meta_title || '',
    meta_description: props.post.meta_description || '',
    image_post_url: props.post.image_post_url || '',
    image_card_url: props.post.image_card_url || '',
    post_html: props.post.post_html || '',
    summary: props.post.summary || '',
    publish_date: props.post.publish_date || '',
    author_id: props.post.author_id || '',
    category_id: props.post.category_id || '',
});

// Función para enviar la actualización del formulario
function submit() {
    form.put(route('posts.update', props.post.id), {
        onSuccess: () => {
            console.log('Post actualizado con éxito.');
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors);
        }
    });
}

// Computed properties para los select de autores y categorías
const authorOptions = computed(() => props.authors.map(author => ({ id: author.id, name: author.name })));
const categoryOptions = computed(() => props.categories.map(category => ({ id: category.id, name: category.name })));

// Watcher para generar automáticamente el slug cuando se cambia el título
watch(() => form.title, (newTitle) => {
    form.slug = slugify(newTitle, { lower: true, strict: true }); // Generamos el slug automáticamente
});
</script>

<template>
    <AppLayout title="Editar Post">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Post
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <!-- Título -->
                    <InputLabel for="title" value="Título" />
                    <TextInput id="title" v-model="form.title" class="block w-full mt-1" type="text" />

                    <!-- Slug (deshabilitado) -->
                    <InputLabel for="slug" value="Slug" />
                    <TextInput id="slug" v-model="form.slug" class="block w-full mt-1" type="text" disabled />

                    <!-- Meta Título -->
                    <InputLabel for="meta_title" value="Meta Título" />
                    <TextInput id="meta_title" v-model="form.meta_title" class="block w-full mt-1" type="text" />

                    <!-- Meta Descripción -->
                    <InputLabel for="meta_description" value="Meta Descripción" />
                    <textarea id="meta_description" v-model="form.meta_description"
                        class="block w-full mt-1"></textarea>

                    <!-- URL de la Imagen del Post -->
                    <InputLabel for="image_post_url" value="URL de la Imagen del Post" />
                    <TextInput id="image_post_url" v-model="form.image_post_url" class="block w-full mt-1" type="url" />

                    <!-- URL de la Imagen de la Tarjeta -->
                    <InputLabel for="image_card_url" value="URL de la Imagen de la Tarjeta" />
                    <TextInput id="image_card_url" v-model="form.image_card_url" class="block w-full mt-1" type="url" />

                    <!-- Contenido del Post (HTML) -->
                    <InputLabel for="post_html" value="Contenido del Post" />
                    <textarea id="post_html" v-model="form.post_html" class="block w-full mt-1"></textarea>

                    <!-- Resumen -->
                    <InputLabel for="summary" value="Resumen" />
                    <textarea id="summary" v-model="form.summary" class="block w-full mt-1"></textarea>

                    <!-- Fecha de Publicación -->
                    <InputLabel for="publish_date" value="Fecha de Publicación" />
                    <TextInput id="publish_date" v-model="form.publish_date" class="block w-full mt-1" type="date" />

                    <!-- Autor (Select) -->
                    <InputLabel for="author_id" value="Autor" />
                    <select id="author_id" v-model="form.author_id" class="block w-full mt-1">
                        <option value="" disabled>Selecciona un autor</option>
                        <option v-for="author in authorOptions" :key="author.id" :value="author.id">
                            {{ author.name }}
                        </option>
                    </select>

                    <!-- Categoría (Select) -->
                    <InputLabel for="category_id" value="Categoría" />
                    <select id="category_id" v-model="form.category_id" class="block w-full mt-1">
                        <option value="" disabled>Selecciona una categoría</option>
                        <option v-for="category in categoryOptions" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

                    <!-- Botón de envío -->
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Actualizar
                            Post</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
