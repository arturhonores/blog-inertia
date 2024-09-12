<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
// import TextArea from '@Components/TextArea.vue';
import { computed } from 'vue';
import { watch } from 'vue';
import slugify from 'slugify'; // Importamos la librería slugify

const props = defineProps({
    authors: Array,
    categories: Array
});

const form = useForm({
    title: '',
    slug: '',
    meta_title: '',
    meta_description: '',
    image_post_url: '',
    image_card_url: '',
    post_html: '',
    summary: '',
    publish_date: '',
    author_id: '',
    category_id: '',
});

function submit() {
    form.post(route('posts.store'));
}

// Computed properties para facilitar el mapeo de opciones en los selects
const authorOptions = computed(() => props.authors.map(author => ({ id: author.id, name: author.name })));
const categoryOptions = computed(() => props.categories.map(category => ({ id: category.id, name: category.name })));

// Watcher para generar automáticamente el slug
watch(() => form.title, (newTitle) => {
    form.slug = slugify(newTitle, { lower: true, strict: true }); // Generamos el slug en minúsculas
});
</script>

<template>
    <AppLayout title="Nuevo Post">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear nuevo Post
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <!-- Título -->
                    <InputLabel for="title" value="Título" />
                    <TextInput id="title" v-model="form.title" class="block w-full mt-1" type="text" />

                    <!-- Slug (deshabilitado ) -->
                    <InputLabel for="slug" value="Slug" />
                    <TextInput id="slug" v-model="form.slug" class="block w-full mt-1" type="text" disabled />

                    <!-- Meta Título -->
                    <InputLabel for="meta_title" value="Meta Título" />
                    <TextInput id="meta_title" v-model="form.meta_title" class="block w-full mt-1" type="text" />

                    <!-- Meta Descripción -->
                    <InputLabel for="meta_description" value="Meta Descripción" />
                    <textarea id="meta_description" v-model="form.meta_description" class="block w-full mt-1" />

                    <!-- Imagen URL -->
                    <InputLabel for="image_post_url" value="URL de la Imagen del Post" />
                    <TextInput id="image_post_url" v-model="form.image_post_url" class="block w-full mt-1" type="url" />

                    <!-- Imagen de la Tarjeta -->
                    <InputLabel for="image_card_url" value="URL de la Imagen de la Tarjeta" />
                    <TextInput id="image_card_url" v-model="form.image_card_url" class="block w-full mt-1" type="url" />

                    <!-- Contenido del Post (HTML) -->
                    <InputLabel for="post_html" value="Contenido del Post" />
                    <textarea id="post_html" v-model="form.post_html" class="block w-full mt-1" />

                    <!-- Resumen -->
                    <InputLabel for="summary" value="Resumen" />
                    <textarea id="summary" v-model="form.summary" class="block w-full mt-1" />

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
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Guardar Post</button>
                    </div>
                </form>

            </div>
        </div>
    </AppLayout>
</template>
