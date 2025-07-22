import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar interceptors para tratamento de erros globais
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // Redirecionar para login se não autenticado
            window.location.href = '/login';
        } else if (error.response?.status === 419) {
            // Token CSRF expirado
            window.location.reload();
        }
        return Promise.reject(error);
    }
);