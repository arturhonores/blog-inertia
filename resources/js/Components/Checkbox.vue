<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: Boolean,  // Ahora será Booleano en lugar de Array
        default: false,
    },
    value: {
        type: [String, Number],
        default: null,
    },
});

// Computed para manejar la selección de un checkbox
const proxyChecked = computed({
    get() {
        return props.checked;  // Ya no es necesario verificar si es un array
    },
    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input v-model="proxyChecked" type="checkbox" :value="value"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
</template>
