import axios from 'axios';

// Configure Axios defaults
// In production, use the APP_URL from environment
const baseURL = import.meta.env.PROD
    ? (window as any).APP_URL || 'https://tecnoweb.org.bo/inf513/grupo18sc/wornerclean/public'
    : window.location.origin + '/wornerclean/public';

axios.defaults.baseURL = baseURL;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Configure for production HTTPS
if (import.meta.env.PROD) {
    axios.defaults.withCredentials = true;
    axios.defaults.headers.common['X-Forwarded-Proto'] = 'https';
}

// Add CSRF token if available
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

// Export axios for use in components
export default axios;
