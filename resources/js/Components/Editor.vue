<script setup>
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits(['update:modelValue']);

// Inicializar el editor
const editor = useEditor({
    content:
        props.modelValue ||
        '<p>Puedes escribir aquí el contenido del post, usa los botones de edición para dar formato al texto 🎉</p>',
    extensions: [StarterKit],
    editorProps: {
        attributes: {
            class:
                'border border-gray-300 bg-white focus:outline-indigo-500 p-4 min-h-[20rem] max-h-[20rem] overflow-y-auto',
        },
    },
    onUpdate({ editor }) {
        const htmlContent = editor.getHTML();
        emit(
            'update:modelValue',
            htmlContent === '<p></p>' || htmlContent.trim() === '' ? '' : htmlContent
        );
    },
});

// Configurar el watch solo después de que el editor esté listo
watch(
    () => editor.value,
    (editorInstance) => {
        if (editorInstance) {
            // Ahora que editor.value está definido, podemos configurar el watch en modelValue
            watch(
                () => props.modelValue,
                (newValue) => {
                    if (editor.value && editor.value.getHTML() !== newValue) {
                        editor.value.commands.setContent(newValue || '');
                    }
                }
            );
        }
    },
    { immediate: true }
);

// Limpiar el editor cuando se desmonte el componente
onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy();
    }
});
</script>

<template>
    <div class="w-full mt-2">
        <section v-if="editor && editor.isEditable"
            class="buttons flex items-center flex-wrap gap-x-3 border-t border-l border-r border-gray-300 p-4">
            <button type="button" @click="editor.chain().focus().toggleBold().run()"
                :disabled="!editor.can().chain().focus().toggleBold().run()" :class="[
                    'px-2 py-1 font-bold rounded-lg italic w-8', // Clases por defecto
                    editor.isActive('bold') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
                ]">
                B
            </button>
            <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                :disabled="!editor.can().chain().focus().toggleItalic().run()" :class="[
                    'px-2 py-1 font-bold rounded-lg italic w-8', // Clases por defecto
                    editor.isActive('italic') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
                ]">
                I
            </button>
            <!-- Agrega más botones si lo necesitas -->
        </section>
        <EditorContent v-if="editor" :editor="editor" />
    </div>
</template>
