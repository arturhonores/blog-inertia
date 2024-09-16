<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, computed, watch } from 'vue';
import slugify from 'slugify'; // generar slug

const props = defineProps({
    authors: Array,
    categories: Array,
});

// Límite de caracteres para el título
const TITLE_MAX_LENGTH = 255;
const META_TITLE_MAX_LENGTH = 100;
const META_DESCRIPTION_MAX_LENGTH = 200;
const SUMMARY_MAX_LENGTH = 180;

// Inicializar formulario
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

// Estado para el contador de caracteres
const titleLength = ref(0);
const metaTitleLength = ref(0);
const metaDescriptionLength = ref(0)
const summaryLength = ref(0)


// Watch para generar el slug y contar los caracteres del título
watch(() => form.title, (newTitle) => {
    titleLength.value = newTitle.length;
    form.slug = slugify(newTitle, { lower: true, strict: true }); // Generar slug automáticamente
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

// Función para validar campos y enviar el formulario
function submit() {
    form.post(route('posts.store'));
}


// Computed properties para los selects
const authorOptions = computed(() => props.authors.map(author => ({ id: author.id, name: author.name })));
const categoryOptions = computed(() => props.categories.map(category => ({ id: category.id, name: category.name })));
</script>

<template>
    <AppLayout title="Nuevo Post">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear nuevo Post
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 lg:px-8">
                <form @submit.prevent="submit">
                    <!-- Título -->
                    <div class="mb-4">
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
                    <div class="mb-4">
                        <div class="flex justify-between">
                            <InputLabel for="slug" value="Slug" />
                            <p class="text-xs">Generado automáticamente</p>
                        </div>
                        <TextInput id="slug" v-model="form.slug" class="block w-full mt-1" type="text" disabled />
                        <!-- Mostrar errores del backend -->
                        <p v-if="form.errors.slug" class="text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <!-- Meta Título -->
                    <div class="mb-4">
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
                    <div class="mb-4">
                        <div class="flex justify-between">
                            <InputLabel for="meta_description" value="Meta Descripción" />
                            <div class="flex gap-4 text-xs">
                                <p>Nº caracteres: {{ metaDescriptionLength }}</p>
                                <p>Max. caracteres: {{ META_DESCRIPTION_MAX_LENGTH }}</p>
                            </div>
                        </div>
                        <TextInput id="meta_description" v-model="form.meta_description" class="block w-full mt-1"
                            type="text" :maxlength="META_DESCRIPTION_MAX_LENGTH" />
                        <p v-if="form.errors.meta_description" class="text-red-500">{{ form.errors.meta_description }}
                        </p>
                    </div>

                    <!-- Imagen URL -->
                    <div class="mb-4">
                        <InputLabel for="image_post_url" value="URL de la Imagen del Post" />
                        <TextInput id="image_post_url" v-model="form.image_post_url" class="block w-full mt-1"
                            type="url" />
                        <p v-if="form.errors.image_post_url" class="text-red-500">{{ form.errors.image_post_url }}</p>
                    </div>

                    <!-- Imagen de la Tarjeta -->
                    <div class="mb-4">
                        <InputLabel for="image_card_url" value="URL de la Imagen de la Tarjeta" />
                        <TextInput id="image_card_url" v-model="form.image_card_url" class="block w-full mt-1"
                            type="url" />
                        <p v-if="form.errors.image_card_url" class="text-red-500">{{ form.errors.image_card_url }}</p>
                    </div>

                    <!-- Contenido del Post (HTML) -->
                    <div class="mb-4">
                        <InputLabel for="post_html" value="Contenido del Post" />
                        <textarea id="post_html" v-model="form.post_html" class="block w-full mt-1"></textarea>
                        <p v-if="form.errors.post_html" class="text-red-500">{{ form.errors.post_html }}</p>
                    </div>

                    <!-- Resumen -->
                    <div class="mb-4">
                        <div class="flex justify-between">
                            <InputLabel for="summary" value="Resumen" />
                            <div class="flex gap-4 text-xs">
                                <p>Nº caracteres: {{ summaryLength }}</p>
                                <p>Max. caracteres: {{ SUMMARY_MAX_LENGTH }}</p>
                            </div>
                        </div>
                        <textarea id="summary" v-model="form.summary" class="block w-full mt-1"
                            :maxlength="SUMMARY_MAX_LENGTH"></textarea>
                        <p v-if="form.errors.summary" class="text-red-500">{{ form.errors.summary }}</p>
                    </div>

                    <!-- Fecha de Publicación -->
                    <div class="mb-4">
                        <InputLabel for="publish_date" value="Fecha de Publicación" />
                        <TextInput id="publish_date" v-model="form.publish_date" class="block w-full mt-1"
                            type="date" />
                        <p v-if="form.errors.publish_date" class="text-red-500">{{ form.errors.publish_date }}</p>
                    </div>

                    <!-- Autor (Select) -->
                    <div class="mb-4">
                        <InputLabel for="author_id" value="Autor" />
                        <select id="author_id" v-model="form.author_id" class="block w-full mt-1">
                            <option value="" disabled>Selecciona un autor</option>
                            <option v-for="author in authorOptions" :key="author.id" :value="author.id">
                                {{ author.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.author_id" class="text-red-500">{{ form.errors.author_id }}</p>
                    </div>

                    <!-- Categoría (Select) -->
                    <div class="mb-4">
                        <InputLabel for="category_id" value="Categoría" />
                        <select id="category_id" v-model="form.category_id" class="block w-full mt-1">
                            <option value="" disabled>Selecciona una categoría</option>
                            <option v-for="category in categoryOptions" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="text-red-500">{{ form.errors.category_id }}</p>
                    </div>

                    <!-- Botón de envío -->
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Guardar Post</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
