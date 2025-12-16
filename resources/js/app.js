import './bootstrap';

// Defer Echo subscription until globals are available
(function initEchoSubscription() {
    let attempts = 0;
    const maxAttempts = 40; // ~20s at 500ms
    const timer = setInterval(() => {
        attempts++;
        const userId = window.authUserId;
        const echo = window.Echo;
        if (userId && echo && typeof echo.private === 'function') {
            try {
                console.log('[Echo] Subscribing to private channel delivery-man.' + userId);
                echo.private(`delivery-man.${userId}`)
                    .listen('.delivery.assigned', (e) => {
                        console.log('[Echo] Event received .delivery.assigned', e);
                        try {
                            const payload = e && (e.order || e.orders || e.data || e);
                            window.dispatchEvent(new CustomEvent('delivery:assigned', { detail: payload }));
                        } catch (_) { /* ignore */ }
                    });
            } finally {
                clearInterval(timer);
            }
        } else if (attempts >= maxAttempts) {
            console.warn('[Echo] Subscription attempt timed out. authUserId:', userId, 'Echo present:', !!echo);
            clearInterval(timer);
        }
    }, 500);
})();