import { ref } from 'vue';

export function useDebounce(fn, delay = 300) {
    const timeout = ref(null);

    const debouncedFn = (...args) => {
        clearTimeout(timeout.value);
        timeout.value = setTimeout(() => {
            fn(...args);
        }, delay);
    };

    return debouncedFn;
}
