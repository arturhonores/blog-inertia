import axios from 'axios';
window.axios = axios;

// Configurar el encabezado por defecto para las peticiones XMLHttpRequest
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


//código adicional por problemas con el https

// Detectar si estamos en producción
const isProduction = import.meta.env.PROD;

// Configurar la URL base de Axios
window.axios.defaults.baseURL = isProduction
    ? 'https://' + window.location.host
    : window.location.origin;
