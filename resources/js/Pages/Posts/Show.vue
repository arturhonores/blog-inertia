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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Post
            </h2>
        </template>

        <div class="container mx-auto py-7 max-w-5xl px-6">
            <div>
                <h1 class="text-3xl">{{ post.title }}</h1>
                <p>{{ post.summary }}</p>
                <img class="w-full" :src="post.image_post_url" alt="">
                <p>{{ post.post_html }}</p>
                <div class="w-16">
                    <img src="https://blogfap.s3.eu-north-1.amazonaws.com/mundo.png" alt="mundo" width="100%">
                </div>
                <p>© 2024 Formación Activa Profesional</p>
            </div>
        </div>

    </AppLayout>
</template>