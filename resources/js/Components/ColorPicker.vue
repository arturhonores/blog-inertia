<!-- ColorPicker.vue -->
<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({

    value: String,
    onChange: Function, // Función para manejar el cambio de color
    id: {
        type: String,
        default: 'color-picker',
    },
    defaultColor: {
        type: String,
        default: '#555555',
    },
    title: {
        type: String,
        default: '', // Por defecto sin título
    },
});

const currentColor = computed(() => {
    return props.value || props.defaultColor;
});
</script>

<template>
    <div class="flex items-center gap-2">
        <input :id="id" type="color" class="w-8 h-8 p-1 bg-stone-200 border border-stone-200 rounded-md cursor-pointer"
            @input="(event) => props.onChange(event.target.value)" :value="currentColor" :title="title">
    </div>
</template>

<style scoped>
/* Puedes agregar estilos adicionales si lo deseas */
</style>
