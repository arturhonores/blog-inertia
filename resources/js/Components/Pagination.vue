<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Array, // Enlaces de paginación proporcionados por Laravel
    search: String,
    categories: Array,
});

// Función para construir la URL de paginación con los filtros aplicados
function buildUrl(baseUrl) {
    // Detectar si estamos en producción o desarrollo
    const isProduction = import.meta.env.PROD; // Detecta si Vite está en modo producción

    // Construir la URL con el esquema correcto según el entorno
    const url = new URL(baseUrl, isProduction ? 'https://' + window.location.host : window.location.origin);

    // const url = new URL(baseUrl, window.location.origin);
    if (props.search) {
        url.searchParams.set('search', props.search);
    }
    if (props.categories && props.categories.length > 0) {
        // Agregar las categorías como un array en la query string
        props.categories.forEach((category, index) => {
            url.searchParams.append(`categories[${index}]`, category);
        });
    }
    return url.toString();
}
</script>

<template>
    <nav v-if="links.length > 1" class="mt-6 flex justify-center">
        <ul class="flex space-x-2">
            <li v-for="link in links" :key="link.label" class="flex">
                <Link v-if="link.url" :href="buildUrl(link.url)" v-html="link.label" :class="[
                    'px-4 py-2 border rounded',
                    link.active ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500 hover:bg-indigo-100'
                ]">
                </Link>
                <span v-else v-html="link.label" class="px-4 py-2 border rounded text-gray-500"></span>
            </li>
        </ul>
    </nav>
</template>
