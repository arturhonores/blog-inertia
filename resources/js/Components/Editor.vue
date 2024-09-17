<script setup>
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import { Link as LinkIcon } from 'lucide-vue-next';

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits(['update:modelValue']);

// Inicializar el editor
const editor = useEditor({
    content:
        props.modelValue ||
        '<p>Puedes escribir aquí el contenido del post, usa los botones de edición para dar formato al texto 🎉</p>',
    extensions: [
        StarterKit,
        Underline,
        Link.configure({
            openOnClick: true,
            defaultProtocol: 'https',
            HTMLAttributes: {
                class: 'text-blue-500 underline cursor-pointer',
                target: '_blank',
                rel: 'noopener noreferrer',
            },
        }),],
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

// Función para agregar o eliminar enlaces
const addOrRemoveLink = () => {
    if (editor.value) {
        if (editor.value.isActive('link')) {
            // Si el enlace está activo, lo eliminamos
            editor.value.chain().focus().unsetLink().run();
        } else {
            // Si el enlace no está activo, solicitamos la URL al usuario
            let url = prompt('Introduce la URL del enlace:');
            if (url) {
                // Validar y formatear la URL
                if (!/^https?:\/\//i.test(url)) {
                    url = 'https://' + url;
                }

                // Ejecutar el comando para agregar el enlace
                editor.value.chain().focus().setLink({ href: url }).run();
            }
        }
    }
};

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
            <button type="button" @click="editor.chain().focus().toggleUnderline().run()"
                :disabled="!editor.can().chain().focus().toggleUnderline().run()" :class="[
                    'px-2 py-1 font-bold rounded-lg w-8', // Clases por defecto
                    editor.isActive('underline') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
                ]">
                U
            </button>
            <button type="button" @click="addOrRemoveLink" :disabled="!editor.can().chain().focus().toggleLink().run()"
                :class="[
                    'px-2 py-1 font-bold rounded-lg w-8 h-8', // Clases por defecto
                    editor.isActive('link') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
                ]">
                <LinkIcon :size="18" />
            </button>
        </section>
        <EditorContent v-if="editor" :editor="editor" />
    </div>
</template>
