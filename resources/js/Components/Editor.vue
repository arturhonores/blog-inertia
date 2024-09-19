<script setup>
import { watch, onBeforeUnmount, computed } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import { Link as LinkIcon } from 'lucide-vue-next';
import { List, ListOrdered, TextQuote, Minus } from 'lucide-vue-next';
import TextStyle from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import ColorPicker from './ColorPicker.vue';

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
        StarterKit.configure({
            heading: {
                levels: [1, 2, 3],
            },
        }),
        Underline,
        Link.configure({
            openOnClick: true,
            defaultProtocol: 'https',
            HTMLAttributes: {
                class: 'text-blue-500 underline cursor-pointer',
                target: '_blank',
                rel: 'noopener noreferrer',
            },
        }),
        TextStyle,
        Color.configure({
            types: ['textStyle'],
        })
    ],
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

// Propiedad computada para el color
const currentColor = computed(() => {
    return editor.value?.getAttributes('textStyle').color || '#555555';
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
    <div class="w-full mt-2 bg-gray-400">
        <section v-if="editor && editor.isEditable"
            class="flex items-center justify-center flex-wrap gap-3 border-t border-l border-r border-gray-300 p-4">
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
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8 text-center text-sm', // Clases por defecto
                editor.isActive('heading', { level: 1 }) ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
            ]">
                H1
            </button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8 text-center text-sm', // Clases por defecto
                editor.isActive('heading', { level: 2 }) ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
            ]">
                H2
            </button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8 text-center text-sm', // Clases por defecto
                editor.isActive('heading', { level: 3 }) ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
            ]">
                H3
            </button>
            <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8', // Clases por defecto
                editor.isActive('bulletList') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
            ]">
                <List :size="18" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8', // Clases por defecto
                editor.isActive('orderedList') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
            ]">
                <ListOrdered :size="18" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8', // Clases por defecto
                editor.isActive('blockquote') ? 'bg-neutral-900 text-white' : 'bg-stone-200', // Clases condicionales
            ]">
                <TextQuote :size="18" />
            </button>
            <button type="button" @click="editor.chain().focus().setHorizontalRule().run()" :class="[
                'px-2 py-1 font-bold rounded-lg w-8 h-8 bg-stone-200']">
                <Minus :size="18" />
            </button>
            <ColorPicker :value="currentColor" :onChange="color => editor.chain().focus().setColor(color).run()"
                id="text-color-input" title="El color por defecto es #555555" />
        </section>
        <EditorContent v-if="editor" :editor="editor" />
    </div>
</template>
