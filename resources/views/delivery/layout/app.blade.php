<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('css/delivery/style.css') }}" rel="stylesheet">

    @vite(['resources/js/app.js'])

</head>

<body>
    <!-- Mobile Header -->
    @include('delivery.layout.mobile-header')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">


        @yield('content')
    </div>

    <!-- Bottom Navigation -->
    @include('delivery.layout.bottom-nav')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        window.authUserId = {{ auth('staff')->id() ?? 'null' }};
    </script>
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const savedTheme = localStorage.getItem('deliveryTheme') || 'light';

        // Apply saved theme on page load
        document.documentElement.setAttribute('data-theme', savedTheme);

        // Toggle theme on button click
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';

                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('deliveryTheme', newTheme);

                // Optional: Show notification
                showNotification(`${newTheme === 'dark' ? 'Dark' : 'Light'} mode activated`, 'success');
            });
        }

        // Section titles for mobile header
        const sectionTitles = {
            dashboard: {
                title: 'Dashboard',
                subtitle: 'Track your deliveries'
            },
            deliveries: {
                title: 'All Deliveries',
                subtitle: 'Manage your orders'
            },
            routes: {
                title: 'Routes',
                subtitle: 'Optimize your path'
            },
            history: {
                title: 'History',
                subtitle: 'Past deliveries'
            },
            profile: {
                title: 'Profile',
                subtitle: 'Manage your account'
            },
            settings: {
                title: 'Settings',
                subtitle: 'App preferences'
            }
        };


        // const navItems = document.querySelectorAll('.nav-item[data-section]');
        // const contentSections = document.querySelectorAll('.content-section');
        // const headerTitle = document.getElementById('headerTitle');
        // const headerSubtitle = document.getElementById('headerSubtitle');


        // Complete Delivery
        function completeDelivery(btn, orderId = null) {
            let row = null;
            if (btn) row = btn.closest('.delivery-item');
            if (!row && btn) row = btn.closest('tr');

            if (row) {
                const badge = row.querySelector('.status-badge') || row.querySelector('.badge-status');
                if (badge) {
                    badge.className = 'badge badge-status bg-success text-white';
                    badge.textContent = 'Completed';
                }

                const actions = row.querySelector('.delivery-actions') || row.querySelector('td:last-child');
                if (actions) {
                    actions.innerHTML =
                        '<button class="btn btn-sm btn-secondary" disabled><i class="fas fa-check-circle"></i></button>';
                }

                // If it's a table row, mark it
                if (row.tagName === 'TR') {
                    row.classList.add('table-success');
                }
            }

            showNotification('Delivery completed successfully!', 'success');
        }

        function viewOrder(orderId) {
            if (!orderId) return;
            window.location.href = '/orders/' + orderId;
        }

        // Show Notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                left: 50%;
                transform: translateX(-50%);
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 12px 24px;
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                font-weight: 600;
                font-size: 14px;
                max-width: 90%;
                text-align: center;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Profile image preview + form helpers
        (function() {
            const photoInput = document.getElementById('photoInput');
            const previewImg = document.getElementById('profilePreviewImg');
            const removeBtn = document.getElementById('removePhotoBtn');
            const removeInput = document.getElementById('removePhotoInput');
            const cancelBtn = document.getElementById('cancelProfile');
            const form = document.getElementById('profileForm');

            // store original values so Cancel restores them
            const original = {};
            if (form) {
                original.name = form.querySelector('input[name="name"]')?.value || '';
                original.email = form.querySelector('input[name="email"]')?.value || '';
                original.phone = form.querySelector('input[name="phone"]')?.value || '';
                original.bio = form.querySelector('textarea[name="bio"]')?.value || '';
                original.preview = previewImg ? previewImg.src : '';
            }

            if (photoInput && previewImg) {
                photoInput.addEventListener('change', (e) => {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    if (!file.type.startsWith('image/')) {
                        showNotification('Please select a valid image file', 'error');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        previewImg.src = ev.target.result;
                        if (removeInput) removeInput.value = '0';
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (removeBtn && previewImg) {
                removeBtn.addEventListener('click', () => {
                    previewImg.src = '{{ asset('images/default-avatar.png') }}';
                    if (photoInput) photoInput.value = '';
                    if (removeInput) removeInput.value = '1';
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    try {
                        if (form.querySelector('input[name="name"]')) form.querySelector('input[name="name"]')
                            .value = original.name;
                        if (form.querySelector('input[name="email"]')) form.querySelector('input[name="email"]')
                            .value = original.email;
                        if (form.querySelector('input[name="phone"]')) form.querySelector('input[name="phone"]')
                            .value = original.phone;
                        if (form.querySelector('textarea[name="bio"]')) form.querySelector(
                            'textarea[name="bio"]').value = original.bio;
                        if (previewImg) previewImg.src = original.preview;
                        if (photoInput) photoInput.value = '';
                        if (removeInput) removeInput.value = '0';
                        showNotification('Changes reverted', 'success');
                    } catch (err) {
                        console.error('Failed to reset profile form', err);
                    }
                });
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
