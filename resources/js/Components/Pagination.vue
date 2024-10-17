<script setup>
import { Link } from '@inertiajs/vue3';
import { SquareChevronRight, SquareChevronLeft } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    links: Array, // Enlaces de paginación proporcionados por Laravel
    search: String,
    categories: Array,
});

// Función para construir la URL de paginación con los filtros aplicados
function buildUrl(baseUrl) {
    const url = new URL(baseUrl, window.location.origin);
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

// Computed para generar los enlaces visibles de paginación
const visibleLinks = computed(() => {
    const maxPagesToShow = 5; // Número máximo de botones de páginas visibles
    const totalLinks = props.links.length;
    const currentPageIndex = props.links.findIndex(link => link.active);
    const linksAroundCurrent = 2;

    // Si hay menos enlaces de los que queremos mostrar, devolvemos todos
    if (totalLinks <= maxPagesToShow) {
        return props.links;
    }

    const firstPage = props.links[0];
    const lastPage = props.links[totalLinks - 1];

    // Generar las páginas visibles
    let pages = [];

    // Agregar primera página
    pages.push(firstPage);

    // Agregar "..." si hay un salto después de la primera página
    if (currentPageIndex - linksAroundCurrent > 1) {
        pages.push({ label: '...', url: null });
    }

    // Páginas alrededor de la actual
    const start = Math.max(1, currentPageIndex - linksAroundCurrent);
    const end = Math.min(totalLinks - 2, currentPageIndex + linksAroundCurrent);

    for (let i = start; i <= end; i++) {
        pages.push(props.links[i]);
    }

    // Agregar "..." si hay un salto antes de la última página
    if (currentPageIndex + linksAroundCurrent < totalLinks - 2) {
        pages.push({ label: '...', url: null });
    }

    // Agregar última página
    pages.push(lastPage);

    return pages;
});
</script>

<template>
    <nav v-if="links.length > 1" class="mt-6 flex justify-center">
        <ul class="flex space-x-2">
            <li v-for="link in visibleLinks" :key="link.label" class="flex">
                <Link v-if="link.url" :href="buildUrl(link.url)" :class="[
                    'px-4 py-2 border rounded',
                    link.active ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500 hover:bg-indigo-100'
                ]">
                <template v-if="link.label === 'pagination.previous'">
                    <!-- Ícono para "anterior" con lucide-vue-next -->
                    <SquareChevronLeft />
                </template>
                <template v-else-if="link.label === 'pagination.next'">
                    <!-- Ícono para "siguiente" con lucide-vue-next -->
                    <SquareChevronRight />
                </template>
                <template v-else>
                    <span v-html="link.label"></span>
                </template>
                <template v-else>
                    <span v-html="link.label"></span>
                </template>
                </Link>
            </li>
        </ul>
    </nav>
</template>
