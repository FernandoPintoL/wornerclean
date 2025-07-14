import axios from 'axios';

// Configure Axios defaults
axios.defaults.baseURL = window.location.origin + '/wornerclean/public';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Add CSRF token if available
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

// Export axios for use in components
export default axios;
