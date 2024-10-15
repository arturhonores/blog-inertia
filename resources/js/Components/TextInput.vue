<script setup>
import { onMounted, ref } from 'vue';

// Definir las props que el componente recibe
defineProps({
    modelValue: String,
    placeholder: {
        type: String,
        default: '',
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input ref="input" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue" :placeholder="placeholder" @input="$emit('update:modelValue', $event.target.value)">
</template>
