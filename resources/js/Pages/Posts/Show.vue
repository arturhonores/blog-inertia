<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

// Definir las props que se reciben desde el backend
const props = defineProps({
    post: Object,
});
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
                                src="https://blogfap.s3.eu-north-1.amazonaws.com/edit-icon.svg" alt="edit icon"
                                width="16px"></span><span>Editar</span></a>
                    <a class="flex items-center gap-1 cursor-pointer text-red-600"><span><img
                                src="https://blogfap.s3.eu-north-1.amazonaws.com/delete-icon.svg" alt="edit icon"
                                width="16px"></span><span>Eliminar</span></a>
                </div>
            </div>
        </template>

        <div class="container mx-auto py-7 max-w-5xl px-6">
            <div>
                <h1 class="text-3xl">{{ post.title }}</h1>
                <p>{{ post.summary }}</p>
                <img class="w-full h-auto" :src="post.image_post_url" alt="">
                <p>{{ post.post_html }}</p>
                <div class="w-16">
                    <img src="https://blogfap.s3.eu-north-1.amazonaws.com/mundo.png" alt="mundo" width="100%">
                </div>
                <p>© 2024 Formación Activa Profesional</p>
            </div>
        </div>

    </AppLayout>
</template>