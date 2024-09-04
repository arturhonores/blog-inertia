<template>
  <div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Últimos Posts</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="post in posts"
        :key="post.id"
        class="bg-white p-6 rounded-lg shadow-lg"
      >
        <img
          :src="post.image_card_url"
          alt="Post Image"
          class="w-full h-48 object-cover rounded-md mb-4"
        />
        <h2 class="text-xl font-semibold mb-2">{{ post.title }}</h2>
        <p class="text-gray-700">{{ post.summary }}</p>
        <a
          :href="`/posts/${post.slug}`"
          class="text-blue-500 hover:underline mt-2 inline-block"
          >Read more</a
        >
      </div>
    </div>
  </div>
</template>
  
  <script>
export default {
  props: {
    posts: Array,
  },
};
</script>
  
  <style scoped>
/* Puedes agregar estilos adicionales si es necesario */
</style>
  