import { ref } from 'vue';

export function useDebounce(fn, delay = 300) {
    const timeout = ref(null);

    const debouncedFn = (...args) => {
        clearTimeout(timeout.value);
        // Log cada vez que se activa debounce
        // console.log("Debounce activado, esperando", delay, "ms");
        timeout.value = setTimeout(() => {
            // Log cuando fn realmente se ejecuta
            // console.log("Debounce completo, ejecutando la función");
            fn(...args);
        }, delay);
    };

    return debouncedFn;
}
