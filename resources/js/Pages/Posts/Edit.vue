<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import Editor from '@/Components/Editor.vue';
import { ref, computed, watch } from 'vue';
import slugify from 'slugify';

const props = defineProps({
    authors: Array,
    categories: Array,
    post: Object, // Recibimos el post a editar
});

// Límites de caracteres
const TITLE_MAX_LENGTH = 255;
const META_TITLE_MAX_LENGTH = 100;
const META_DESCRIPTION_MAX_LENGTH = 200;
const SUMMARY_MAX_LENGTH = 700;

// Inicializamos el formulario con los datos existentes del post
const form = useForm({
    title: props.post.title || '',
    slug: props.post.slug || '',
    meta_title: props.post.meta_title || '',
    meta_description: props.post.meta_description || '',
    image_post_url: null,
    image_card_url: null,
    post_html: props.post.post_html || '',
    summary: props.post.summary || '',
    publish_date: props.post.publish_date || '',
    author_id: props.post.author_id || '',
    category_id: props.post.category_id || '',
    //según documentacion de inertia
    _method: 'PUT'
});

// Estado para los contadores de caracteres
const titleLength = ref(form.title.length);
const metaTitleLength = ref(form.meta_title.length);
const metaDescriptionLength = ref(form.meta_description.length);
const summaryLength = ref(form.summary.length);

// Watchers para actualizar contadores de caracteres y generar slug
watch(() => form.title, (newTitle) => {
    titleLength.value = newTitle.length;
    form.slug = slugify(newTitle, { lower: true, strict: true });
});

// Watch para contar los caracteres del meta título
watch(() => form.meta_title, (newMetaTitle) => {
    metaTitleLength.value = newMetaTitle.length;
});

// Watch para contar los caracteres del meta descripción
watch(() => form.meta_description, (newMetaDescription) => {
    metaDescriptionLength.value = newMetaDescription.length;
});

// Watch para contar los caracteres del resumen
watch(() => form.summary, (newSummary) => {
    summaryLength.value = newSummary.length;
});

// Función para enviar la actualización del formulario
function submit() {
    form.post(route('posts.update', props.post.id), {
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
</script>


<template>
    <AppLayout title="Editar Post">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Post</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 lg:px-8">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <!-- Título -->
                    <div class="mb-8">
                        <div class="flex justify-between">
                            <InputLabel for="title" value="Título" />
                            <div class="flex gap-4 text-xs">
                                <p>Nº caracteres: {{ titleLength }}</p>
                                <p>Max. caracteres: {{ TITLE_MAX_LENGTH }}</p>
                            </div>
                        </div>
                        <TextInput id="title" v-model="form.title" class="block w-full mt-1" type="text"
                            :maxlength="TITLE_MAX_LENGTH" />
                        <!-- Mostrar errores del backend -->
                        <p v-if="form.errors.title" class="text-red-500">{{ form.errors.title }}</p>
                    </div>

                    <!-- Slug (deshabilitado) -->
                    <div class="mb-8">
                        <div class="flex justify-between">
                            <InputLabel for="slug" value="Slug" />
                            <p class="text-xs">Generado automáticamente</p>
                        </div>
                        <TextInput id="slug" v-model="form.slug" class="block w-full mt-1" type="text" disabled />
                        <!-- Mostrar errores del backend -->
                        <p v-if="form.errors.slug" class="text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <!-- Meta Título -->
                    <div class="mb-8">
                        <div class="flex justify-between">
                            <InputLabel for="meta_title" value="Meta Título" />
                            <div class="flex gap-4 text-xs">
                                <p>Nº caracteres: {{ metaTitleLength }}</p>
                                <p>Max. caracteres: {{ META_TITLE_MAX_LENGTH }}</p>
                            </div>
                        </div>
                        <TextInput id="meta_title" v-model="form.meta_title" class="block w-full mt-1" type="text"
                            :maxlength="META_TITLE_MAX_LENGTH" />
                        <p v-if="form.errors.meta_title" class="text-red-500">{{ form.errors.meta_title }}</p>
                    </div>

                    <!-- Meta Descripción -->
                    <div class="mb-8">
                        <div class="flex justify-between">
                            <InputLabel for="meta_description" value="Meta Descripción" />
                            <div class="flex gap-4 text-xs">
                                <p>Nº caracteres: {{ metaDescriptionLength }}</p>
                                <p>Max. caracteres: {{ META_DESCRIPTION_MAX_LENGTH }}</p>
                            </div>
                        </div>
                        <TextArea id="meta_description" v-model="form.meta_description" :rows="2"
                            class="block w-full mt-1" :maxlength="META_DESCRIPTION_MAX_LENGTH" />
                        <p v-if="form.errors.meta_description" class="text-red-500">{{ form.errors.meta_description }}
                        </p>
                    </div>

                    <div class="flex gap-3 flex-wrap mb-8">
                        <div class="grow">
                            <p>Imagen actual del post</p>
                            <!-- Previsualización de la imagen del post -->
                            <div v-if="props.post.image_post_url" class="h-40">
                                <img :src="props.post.image_post_url" alt="Imagen actual del post"
                                    class="w-auto h-36" />
                            </div>
                            <!-- Input para subir nueva imagen del post -->
                            <div class="mb-4">
                                <InputLabel for="image_post_url" value="Subir nueva imagen del post (MAX. 5MB)" />
                                <input type="file" @input="form.image_post_url = $event.target.files[0]"
                                    id="image_post_url" name="image_post_url"
                                    class="block w-full mt-1 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-gray-300 file:border-0 file:me-4 file:py-2 file:px-4" />
                                <p v-if="form.errors.image_post_url" class="text-red-500">{{ form.errors.image_post_url
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="grow">
                            <p>Imagen actual de la tarjeta resumen del post</p>
                            <!-- Previsualización de la imagen de la tarjeta -->
                            <div v-if="props.post.image_card_url" class="h-40">
                                <img :src="props.post.image_card_url" alt="Imagen actual de la tarjeta"
                                    class="w-auto h-28" />
                            </div>
                            <!-- Input para subir nueva imagen de la tarjeta -->
                            <div class="mb-4">
                                <InputLabel for="image_card_url" value="Subir nueva imagen de la tarjeta (MAX. 5MB)" />
                                <input type="file" @input="form.image_card_url = $event.target.files[0]"
                                    id="image_card_url" name="image_card_url"
                                    class="block w-full mt-1 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-gray-300 file:border-0 file:me-4 file:py-2 file:px-4" />
                                <p v-if="form.errors.image_card_url" class="text-red-500">{{ form.errors.image_card_url
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen -->
                    <div class="mb-8">
                        <div class="flex justify-between">
                            <InputLabel for="summary" value="Resumen" />
                            <div class="flex gap-4 text-xs">
                                <p>Nº caracteres: {{ summaryLength }}</p>
                                <p>Max. caracteres: {{ SUMMARY_MAX_LENGTH }}</p>
                            </div>
                        </div>
                        <TextArea id="summary" v-model="form.summary" class="block w-full mt-1"
                            :maxlength="SUMMARY_MAX_LENGTH"></TextArea>
                        <p v-if="form.errors.summary" class="text-red-500">{{ form.errors.summary }}</p>
                    </div>

                    <!-- Contenido del Post (HTML) usando el componente Editor -->
                    <div class="mb-8">
                        <InputLabel for="post_html" value="Contenido del Post" />
                        <Editor v-model="form.post_html" />
                        <p v-if="form.errors.post_html" class="text-red-500">{{ form.errors.post_html }}</p>
                    </div>

                    <div class="flex gap-3 flex-wrap mb-8">
                        <!-- Fecha de Publicación -->
                        <div class="mb-4 grow">
                            <InputLabel for="publish_date" value="Fecha de Publicación" />
                            <TextInput id="publish_date" v-model="form.publish_date" class="block w-full mt-1"
                                type="date" />
                            <p v-if="form.errors.publish_date" class="text-red-500">{{ form.errors.publish_date }}</p>
                        </div>

                        <!-- Autor (Select) -->
                        <div class="mb-4 grow">
                            <InputLabel for="author_id" value="Autor" />
                            <Select id="author_id" v-model="form.author_id" :options="authorOptions"
                                :autofocus="true" />
                            <p v-if="form.errors.author_id" class="text-red-500">{{ form.errors.author_id }}</p>
                        </div>

                        <!-- Categoría (Select) -->
                        <div class="mb-4 grow">
                            <InputLabel for="category_id" value="Categoría" />
                            <Select id="category_id" v-model="form.category_id" :options="categoryOptions"
                                :autofocus="true" />
                            <p v-if="form.errors.category_id" class="text-red-500">{{ form.errors.category_id }}</p>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg">Actualizar
                            Post</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
