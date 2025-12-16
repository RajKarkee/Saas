import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Attach CSRF token for broadcasting auth and other POSTs
const csrfTokenTag = document.head.querySelector('meta[name="csrf-token"]');
if (csrfTokenTag && csrfTokenTag.content) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfTokenTag.content;
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
