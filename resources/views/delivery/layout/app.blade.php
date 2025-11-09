<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
        }

        [data-theme="dark"] {
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #334155;
            --body-bg: #0f172a;
            --card-bg: #1e293b;
        }

        [data-theme="dark"] .mobile-header {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] .bottom-nav {
            box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] .card-container {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--body-bg);
            padding-bottom: 80px;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Mobile Top Header */
        .mobile-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--card-bg);
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .header-left p {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
        }

        .header-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .header-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--body-bg);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 18px;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .header-btn:hover {
            transform: scale(1.05);
        }

        .header-btn:active {
            transform: scale(0.95);
        }

        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--body-bg);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle-btn:hover {
            transform: rotate(180deg);
        }

        .theme-toggle-btn:active {
            transform: scale(0.9);
        }

        [data-theme="dark"] .theme-toggle-btn .sun-icon {
            display: block;
        }

        [data-theme="dark"] .theme-toggle-btn .moon-icon {
            display: none;
        }

        [data-theme="light"] .theme-toggle-btn .sun-icon {
            display: none;
        }

        [data-theme="light"] .theme-toggle-btn .moon-icon {
            display: block;
        }

        .header-btn .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 18px;
            height: 18px;
            background: var(--danger-color);
            border-radius: 50%;
            font-size: 10px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid var(--border-color);
            transition: border-color 0.3s ease;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Main Content */
        .main-content {
            padding: 0;
            max-width: 100%;
            margin: 0 auto;
        }

        .content-section {
            display: none;
            padding: 20px;
            min-height: calc(100vh - 160px);
        }

        .content-section.active {
            display: block;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Cards */
        .card-container {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        /* Table Responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            font-size: 13px;
            margin-bottom: 0;
            color: var(--text-primary);
        }

        /* Mobile-friendly stacked table: hide the header and show rows as cards */
        @media (max-width: 767px) {
            .table {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                background: var(--card-bg);
                border: 1px solid var(--border-color);
                border-radius: 12px;
                padding: 10px;
                margin-bottom: 12px;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 6px;
                white-space: normal;
                border-bottom: none;
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--text-secondary);
                margin-right: 8px;
                flex: 0 0 auto;
            }

            .table tbody td .btn {
                margin-left: 6px;
            }

            .table-responsive {
                padding-left: 6px;
                padding-right: 6px;
            }
        }

        .table th {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
            padding: 12px 8px;
            background: var(--body-bg);
        }

        .table td {
            padding: 12px 8px;
            vertical-align: middle;
            white-space: nowrap;
            border-bottom: 1px solid var(--border-color);
        }

        .table-hover tbody tr:hover {
            background-color: var(--body-bg);
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
            margin: 2px;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--card-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-around;
            padding: 8px 0 12px 0;
            z-index: 1000;
            box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.08);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 0 0 3px 3px;
        }

        .nav-item i {
            font-size: 22px;
        }

        .nav-item span {
            font-size: 11px;
            font-weight: 500;
        }

        /* Profile Form Mobile Optimized */
        .profile-preview {
            margin: 20px auto !important;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            font-size: 14px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: var(--card-bg);
            color: var(--text-primary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-state h3 {
            font-size: 18px;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .empty-state p {
            color: var(--text-secondary);
            font-size: 13px;
        }

        /* Desktop */
        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }

            .mobile-header {
                display: none;
            }

            .bottom-nav {
                display: none;
            }

            .main-content {
                max-width: 1200px;
                padding: 20px;
            }

            .content-section {
                padding: 30px;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>

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
        < script src = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js" >
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
