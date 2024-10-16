<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import Editor from '@/Components/Editor.vue';
import MultiSelectTags from '@/Components/MultiSelectTags.vue';
import { ref, computed, watch } from 'vue';
import slugify from 'slugify'; // generar slug

const props = defineProps({
    authors: Array,
    categories: Array,
    tags: Array,
});

// Límite de caracteres para el título
const TITLE_MAX_LENGTH = 255;
const META_TITLE_MAX_LENGTH = 100;
const META_DESCRIPTION_MAX_LENGTH = 200;
const SUMMARY_MAX_LENGTH = 700;

// Inicializar formulario
const form = useForm({
    title: '',
    slug: '',
    meta_title: '',
    meta_description: '',
    image_post_url: null,
    image_card_url: null,
    post_html: '',
    summary: '',
    publish_date: '',
    author_id: '',
    category_id: '',
    tags: [],
});

// Estado para controlar el envío
const isSubmitting = ref(false);

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

// Watch para el contenido del editor
watch(() => form.post_html, (newContent) => {
    //para acciones adicionales cuando el contenido del editor cambie, si es necesario.
});

//verificar cualquier cambio inesperado en props.tags
watch(() => props.tags, (newTags) => {
    console.log('Tags actualizados:', newTags);
});
// Función para validar campos y enviar el formulario
// function submit() {
//     form.post(route('posts.store'));
// }
// Función para validar campos y enviar el formulario
async function submit() {
    if (isSubmitting.value) return;

    isSubmitting.value = true;
    try {
        await form.post(route('posts.store'));
    } catch (error) {
        console.error(error);
    } finally {
        isSubmitting.value = false;
    }
}


// Computed properties para los selects
const authorOptions = computed(() => props.authors.map(author => ({ id: author.id, name: author.name })));
const categoryOptions = computed(() => props.categories.map(category => ({ id: category.id, name: category.name })));

// Transformar los tags para que tengan 'value' y 'label'
const tagOptions = computed(() => props.tags.map(tag => ({
    value: tag.id,
    label: tag.name
})));


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
                            class="block w-full mt-1" type="text" :maxlength="META_DESCRIPTION_MAX_LENGTH" />
                        <p v-if="form.errors.meta_description" class="text-red-500">{{ form.errors.meta_description }}
                        </p>
                    </div>
                    <!-- Tags (Multiselect) -->
                    <div class="mb-8">
                        <InputLabel for="tags" value="Tags" />
                        <MultiSelectTags :initial-tags="tagOptions" v-model="form.tags" />
                        <p v-if="form.errors.tags" class="text-red-500">{{ form.errors.tags }}</p>
                    </div>

                    <div class="flex gap-3 flex-wrap mb-8">
                        <!-- Imagen del Post (Input File) -->
                        <div class="mb-4 grow">
                            <InputLabel for="image_post_url" value="Imagen del Post (MAX. 5MB)" />
                            <input id="image_post_url" name="image_post_url" type="file"
                                @change="form.image_post_url = $event.target.files[0]"
                                class="block w-full mt-1 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-gray-300 file:border-0 file:me-4 file:py-2 file:px-4" />
                            <p v-if="form.errors.image_post_url" class="text-red-500">{{ form.errors.image_post_url }}
                            </p>
                        </div>

                        <!-- Imagen de la Tarjeta (Input File) -->
                        <div class="mb-4 grow">
                            <InputLabel for="image_card_url" value="Imagen de la Tarjeta (MAX. 5MB)" />
                            <input id="image_card_url" name="image_card_url" type="file"
                                @change="form.image_card_url = $event.target.files[0]"
                                class="block w-full mt-1 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-gray-300 file:border-0 file:me-4 file:py-2 file:px-4" />
                            <p v-if="form.errors.image_card_url" class="text-red-500">{{ form.errors.image_card_url }}
                            </p>
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
                        <!-- <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg">Guardar
                            Post</button> -->
                        <button type="submit" :disabled="isSubmitting"
                            class="flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg">
                            <svg v-if="isSubmitting" class="animate-spin h-5 w-5 mr-2 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                </path>
                            </svg>
                            {{ isSubmitting ? 'Guardando...' : 'Guardar Post' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
