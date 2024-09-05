<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

// Definir las props que se reciben desde el backend
const props = defineProps({
  posts: Array, // Definir el tipo de la propiedad posts como un Array
});
</script>

<template>
  <AppLayout title="Posts">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Últimos Posts
      </h2>
    </template>

    <div class="container mx-auto pt-7">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="post in posts" :key="post.id" class="bg-white p-6 rounded-lg shadow-lg">
          <img :src="post.image_card_url" alt="Post Image" class="w-full h-48 object-cover rounded-md mb-4" />
          <h2 class="text-xl font-semibold mb-2">{{ post.title }}</h2>
          <p class="text-gray-700">{{ post.summary }}</p>
          <a :href="`/posts/${post.slug}`" class="text-blue-500 hover:underline mt-2 inline-block">Read more</a>
        </div>
      </div>
    </div>

  </AppLayout>
</template>
