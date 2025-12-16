import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Enable debug logs in development to inspect auth/subscription issues
try { if (import.meta && import.meta.env && import.meta.env.DEV) { window.Pusher.logToConsole = true; } } catch (_) { }

const csrf = document.head.querySelector('meta[name="csrf-token"]')?.content;

// Use default Pusher Cloud endpoints (key + cluster); avoid overriding host/port
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {}
    },
});
