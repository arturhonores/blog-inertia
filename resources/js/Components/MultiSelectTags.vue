<template>
    <div>
        <label for="tags" class="block text-sm font-medium text-gray-700">Selecciona los tags</label>
        <multiselect v-model="selectedTags" mode="tags" :track-by="'value'" :label="'label'" :close-on-select="false"
            :multiple="true" :options="options" :searchable="false" placeholder="Selecciona tags" :create-option="false"
            :object="false" class="mt-1 border border-gray-300 rounded-lg w-full"
            :classes="{ tag: 'bg-indigo-500 text-white text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap min-w-0 rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1', containerActive: 'ring ring-indigo-500 ring-opacity-30' }" />
    </div>
</template>

<script>
import { computed } from 'vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

export default {
    name: 'MultiSelectTags',
    components: { Multiselect },
    props: {
        modelValue: {
            type: Array,
            default: () => [],
        },
        initialTags: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const options = computed(() => {
            if (Array.isArray(props.initialTags)) {
                return props.initialTags;
            } else {
                console.error('initialTags no es un array válido');
                return [];
            }
        });

        const selectedTags = computed({
            get() {
                return props.modelValue;
            },
            set(newTags) {
                if (Array.isArray(newTags)) {
                    console.log('Emitiendo nuevos tags:', newTags);
                    emit('update:modelValue', newTags);
                } else if (newTags === null || newTags === undefined) {
                    console.log('Emitiendo arreglo vacío porque newTags no es un array válido:', newTags);
                    emit('update:modelValue', []);
                } else {
                    console.error('newTags no es un array válido:', newTags);
                }
            },
        });

        // Verificar si los tags llegan correctamente
        console.log('Tags disponibles:', options.value);

        return {
            options,
            selectedTags,
        };
    },
};
</script>

<style scoped>
@import '@vueform/multiselect/themes/tailwind.css';
</style>
