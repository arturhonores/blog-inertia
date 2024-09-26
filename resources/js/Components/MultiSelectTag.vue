<template>
    <div>
        <label for="tags">Selecciona los tags</label>
        <multiselect v-model="selectedTags" :options="tags" :multiple="true" :taggable="true" track-by="name"
            label="name" placeholder="Selecciona o crea nuevos tags" />
    </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import Multiselect from 'vue-multiselect';

export default {
    components: { Multiselect },
    props: {
        initialTags: {
            type: Array,
            default: () => []
        },
        selectedTagsProp: {
            type: Array,
            default: () => []
        },
    },
    setup(props, { emit }) {
        const tags = ref(props.initialTags);
        const selectedTags = ref(props.selectedTagsProp);

        // Emitimos el valor actualizado al componente padre cada vez que cambian los tags seleccionados
        watch(selectedTags, (newTags) => {
            emit('updateTags', newTags);
        });

        return {
            tags,
            selectedTags
        };
    }
};
</script>

<style scoped src="vue-multiselect/dist/vue-multiselect.min.css"></style>