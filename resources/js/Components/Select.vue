<script setup>
import { ref, onMounted } from 'vue';

// Definir correctamente los props con un valor por defecto para autofocus
const props = defineProps({
    modelValue: [String, Number], // Puede ser String o Number
    options: {
        type: Array,
        required: true, // Array de opciones (id, name)
    },
    autofocus: {
        type: Boolean,
        default: false, // Establecer valor por defecto de autofocus en false
    },
});

defineEmits(['update:modelValue']);

const select = ref(null);

// Ejecutar autofocus solo si el prop autofocus es true
onMounted(() => {
    if (props.autofocus && select.value) {
        select.value.focus();
    }
});

// Exponer la función de enfoque para que pueda ser usada desde el componente padre
defineExpose({ focus: () => select.value.focus() });
</script>

<template>
    <select ref="select"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
        :value="modelValue" @change="$emit('update:modelValue', $event.target.value)">
        <option value="" disabled>Seleccione una opción</option>
        <option v-for="option in options" :key="option.id" :value="option.id">
            {{ option.name }}
        </option>
    </select>
</template>
