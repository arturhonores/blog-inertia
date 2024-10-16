<script setup>
import { watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useDebounce } from '@/utils/debounce';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CalendarDays } from 'lucide-vue-next';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';

// Definir las props que se reciben desde el backend
const props = defineProps({
  posts: Array,
  filters: Object,
});

// Usar useForm para manejar los filtros
const form = useForm({
  search: props.filters.search || '',
  categories: props.filters.categories || [],
});

// Método para manejar la selección de categorías
const toggleCategory = (categoryId) => {
  if (form.categories.includes(categoryId)) {
    // Eliminar la categoría si ya está seleccionada
    form.categories = form.categories.filter(id => id !== categoryId);
  } else {
    // Agregar la categoría si no está seleccionada
    form.categories.push(categoryId);
  }
  applyFilters();  // Aplicar filtros inmediatamente después de cambiar la categoría
};

// Función para aplicar los filtros con debounce
const applyFilters = useDebounce(() => {
  form.get('/posts', {
    preserveState: true,
    replace: true,
  });
}, 300);

// Watch para monitorear los cambios en los filtros de búsqueda
watch(() => form.search, applyFilters);

</script>

<template>
  <AppLayout title="Posts">
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-center gap-y-4 md:gap-4">
        <div class="w-full md:w-1/2 md:pr-4">
          <TextInput v-model="form.search" class="w-full" placeholder="Buscar por título" />
        </div>
        <div
          class="flex justify-center md:justify-end items-center gap-4 w-full md:w-1/2 flex-wrap sm:flex-nowrap sm:pl-4">
          <div class="flex items-center gap-2">
            <Checkbox id="caninos" :checked="form.categories.includes(2)" @update:checked="toggleCategory(2)" />
            <InputLabel for="caninos" value="Caninos" />
          </div>
          <div class="flex items-center gap-2">
            <Checkbox id="sanitarios" :checked="form.categories.includes(3)" @update:checked="toggleCategory(3)" />
            <InputLabel for="sanitarios" value="Sanitarios" />
          </div>
          <div class="flex items-center gap-2">
            <Checkbox id="infantil" :checked="form.categories.includes(4)" @update:checked="toggleCategory(4)" />
            <InputLabel for="infantil" value="Infantil" />
          </div>
          <div class="flex items-center gap-2">
            <Checkbox id="oposiciones" :checked="form.categories.includes(1)" @update:checked="toggleCategory(1)" />
            <InputLabel for="oposiciones" value="Oposiciones" />
          </div>
        </div>
      </div>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-7">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="post in posts" :key="post.id" class="bg-white p-6 rounded-lg shadow-lg">
          <div class="h-[247px] w-full mb-3">
            <img :src="post.image_card_url" alt="Card Image" class="h-full w-full object-cover rounded-md mb-4" />
          </div>
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
