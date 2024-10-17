import axios from 'axios';
window.axios = axios;

// Configurar el encabezado por defecto para las peticiones XMLHttpRequest
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
