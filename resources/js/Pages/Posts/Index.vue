<!-- index.vue -->
<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useDebounce } from '@/utils/debounce';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CalendarDays } from 'lucide-vue-next';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Pagination from '@/Components/Pagination.vue';

// Definir las props que se reciben desde el backend
const props = defineProps({
  posts: Object,
  filters: Object,
});

// Definir todas las categorías disponibles
const allCategories = ref([
  { id: 1, name: 'Oposiciones' },
  { id: 2, name: 'Caninos' },
  { id: 3, name: 'Sanitarios' },
  { id: 4, name: 'Infantil' },
]);

// Usar useForm para manejar los filtros y establecer los valores iniciales
const form = useForm({
  search: props.filters.search || '',
  categories: [...(props.filters.categories || []).map(id => Number(id))],
});

// Watch para sincronizar el estado del formulario si las props cambian (por ejemplo, al cambiar de página)
watch(() => props.filters, () => {
  form.search = props.filters.search || '';
  form.categories = [...(props.filters.categories || []).map(id => Number(id))];
});

// Método para manejar la selección de categorías
const toggleCategory = (categoryId, value) => {
  if (value) {
    if (!form.categories.includes(categoryId)) {
      form.categories.push(categoryId);
    }
  } else {
    form.categories = form.categories.filter(id => id !== categoryId);
  }

  applyFilters();
};

// Función para aplicar los filtros con debounce
const applyFilters = useDebounce(() => {
  form.get('/posts', {
    preserveState: true,
    replace: true,
  });
}, 300);

// Watch para monitorear los cambios en los filtros de búsqueda y aplicarlos con debounce
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
          <div v-for="category in allCategories" :key="category.id" class="flex items-center gap-2">
            <Checkbox :checked="form.categories.includes(category.id)"
              @update:checked="value => toggleCategory(category.id, value)" />
            <InputLabel :value="category.name" />
          </div>
        </div>
      </div>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-7">
      <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="post in posts.data" :key="post.id" class="bg-white p-6 rounded-lg shadow-lg">
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
          <a :href="`/posts/${post.id}`" class="text-indigo-500 hover:underline mt-2 inline-block">Ver post</a>
        </div>
      </div>
      <div v-else class="text-center text-gray-500">
        No hay posts disponibles con los filtros aplicados.
      </div>
      <Pagination :links="posts.links" />
    </div>
  </AppLayout>
</template>
