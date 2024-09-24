<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { CalendarDays } from 'lucide-vue-next';

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

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-7">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="post in posts" :key="post.id" class="bg-white p-6 rounded-lg shadow-lg">
          <img :src="post.image_card_url" alt="Card Image" class="w-full h-auto object-cover rounded-md mb-4" />
          <h2 class="text-xl font-semibold mb-2">{{ post.title }}</h2>
          <div class="text-gray-500 mb-2 flex items-center gap-2">
            <p>
              <CalendarDays size="16" />
            </p>
            <p>{{
              new Date(post.publish_date).getDate() +
              ' ' +
              new Date(post.publish_date).toLocaleString('es-ES', { month: 'long' }) +
              ' ' +
              new Date(post.publish_date).getFullYear()
            }}</p>
          </div>
          <p class="text-gray-700 truncate-resumen-card">{{ post.summary }}</p>
          <a :href="`/posts/${post.id}`" class="text-blue-500 hover:underline mt-2 inline-block">Ver post</a>
        </div>
      </div>
    </div>

  </AppLayout>
</template>
