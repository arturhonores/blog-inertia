<script setup>
import { ref, onMounted } from 'vue';

defineProps({
    modelValue: String,
    autofocus: Boolean, // Para manejar el autofocus
    rows: {
        type: Number,
        default: 4, // Valor predeterminado para las filas
    },
});

defineEmits(['update:modelValue']);

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

// Exponer la función de foco para ser llamada desde el componente padre si es necesario
defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea ref="textarea" :value="modelValue" :rows="rows"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
        @input="$emit('update:modelValue', $event.target.value)"></textarea>
</template>
