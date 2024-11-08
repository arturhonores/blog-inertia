<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Definir las props que se reciben desde el backend
const props = defineProps({
    post: Object,
});

// Estado para controlar la visibilidad del modal y el formulario
const showModal = ref(false);
const form = useForm({});

// Función para mostrar el modal de confirmación
function confirmDelete() {
    showModal.value = true;
}

// Función para eliminar el post
function deletePost() {
    form.delete(route('posts.destroy', props.post.id), {
        onSuccess: () => {
            showModal.value = false;
        },
    });
}

</script>

<template>
    <AppLayout title="Posts">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Post
                </h2>
                <div class="flex gap-4">
                    <a :href="route('posts.edit', post.id)"
                        class="flex items-center gap-1 cursor-pointer text-green-600"><span><img
                                src="https://blog-fap.s3.eu-west-3.amazonaws.com/edit-icon.svg" alt="edit icon"
                                width="16px"></span><span>Editar</span></a>
                    <a @click="confirmDelete" class="flex items-center gap-1 cursor-pointer text-red-600"><span><img
                                src="https://blog-fap.s3.eu-west-3.amazonaws.com/delete-icon.svg" alt="delete icon"
                                width="16px"></span><span>Eliminar</span></a>
                </div>
            </div>
        </template>

        <div class="container mx-auto py-7 max-w-5xl px-6">
            <div>
                <h1 class="tiptap-h1">{{ post.title }}</h1>
                <p class="tiptap-p">{{ post.summary }}</p>
                <p class="tiptap-date">{{ new Date(post.publish_date).toLocaleDateString("es-Es", {
                    day: "numeric", month: "long",
                    year: "numeric"
                })
                    }}</p>
                <img class="w-full h-auto rounded-xl" :src="post.image_post_url" alt="imagen del post">
                <div v-html="post.post_html" class="my-3 tiptap"></div>
                <div class="w-16 mt-8">
                    <img src="https://blog-fap.s3.eu-west-3.amazonaws.com/mundoPost.webp" alt="mundo" width="100%">
                </div>
                <p class="tiptap-p">© {{ new Date(post.publish_date).getFullYear() }} Formación Activa Profesional</p>
            </div>
            <!-- Mostrar los tags si existen -->
            <div v-if="post.tags && post.tags.length" class="mt-4">
                <div class="flex gap-2">
                    <h3 class="text-lg">Tags:</h3>
                    <div class="flex gap-2 flex-wrap">
                        <span v-for="tag in post.tags" :key="tag.id"
                            class="bg-indigo-500 text-white text-sm font-semibold py-1 px-3 rounded">
                            {{ tag.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <Modal :show="showModal" @close="showModal = false">
            <template #title>Confirmar eliminación</template>
            <template #content>
                ¿Estás seguro de que deseas eliminar este post? Esta acción no se puede deshacer.
            </template>
            <template #footer>
                <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded-lg">Cancelar</button>
                <button @click="deletePost" class="px-4 py-2 bg-red-500 text-white rounded-lg ms-2">Eliminar</button>
            </template>
        </Modal>

    </AppLayout>
</template>