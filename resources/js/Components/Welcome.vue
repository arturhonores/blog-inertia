<script setup>
import BarChart from './BarChart.vue';
import PieCategories from './PieCategories.vue';
import PieTags from './PieTags.vue';
import { defineProps } from 'vue'

const props = defineProps({

    postsByYear: {
        type: Array,
        required: true
    },
    postsByCategory2024: {
        type: Array,
        required: true
    },
    postsByTag2024: {
        type: Array,
        required: true
    }
})

</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h2 class="mt-8 text-xl font-medium text-gray-900 mb-4">
                Número de Posts por Año
            </h2>
            <div class="w-full h-60">
                <BarChart :postsByYear="postsByYear" />
            </div>
        </div>
        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <h2 class="ms-3 text-xl font-semibold text-gray-900">
                    Posts por Categorías 2024
                </h2>
                <div>
                    <PieCategories :postsByCategory2024="postsByCategory2024" />
                </div>
            </div>
            <div>
                <h2 class="ms-3 text-xl font-semibold text-gray-900">
                    Posts por Tags 2024
                </h2>
                <div>
                    <PieTags :postsByTag2024="postsByTag2024" />
                </div>
            </div>
        </div>
    </div>
</template>
