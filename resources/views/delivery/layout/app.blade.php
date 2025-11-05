<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href='{{ asset('css/delivery/index.css') }}'>

</head>

<body>
    <!-- Sidebar -->
    @include('delivery.layout.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->

        @include('delivery.layout.topnav')
        <!-- Dashboard Section -->
        @include('delivery.dashboard')

        <!-- Deliveries Section -->
        <div class="content-container content-section" id="deliveries">
            <div class="page-header">
                <h1 class="page-title">All Deliveries</h1>
                <p class="page-subtitle">Manage and track all your delivery orders</p>
            </div>
            <div class="card-container">
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>Deliveries Management</h3>
                    <p>View and manage all your deliveries in one place</p>
                </div>
            </div>
        </div>

        <!-- Routes Section -->
        <div class="content-container content-section" id="routes">
            <div class="page-header">
                <h1 class="page-title">Delivery Routes</h1>
                <p class="page-subtitle">Optimize your delivery routes for efficiency</p>
            </div>
            <div class="card-container">
                <div class="empty-state">
                    <i class="fas fa-route"></i>
                    <h3>Route Planning</h3>
                    <p>Plan and optimize your delivery routes</p>
                </div>
            </div>
        </div>

        <!-- Earnings Section -->
        {{-- <div class="content-container content-section" id="earnings">
            <div class="page-header">
                <h1 class="page-title">Earnings</h1>
                <p class="page-subtitle">Track your earnings and payment history</p>
            </div>
            <div class="card-container">
                <div class="empty-state">
                    <i class="fas fa-chart-line"></i>
                    <h3>Earnings Overview</h3>
                    <p>View your earnings statistics and payment details</p>
                </div>
            </div>
        </div> --}}

        <!-- History Section -->
        <div class="content-container content-section" id="history">
            <div class="page-header">
                <h1 class="page-title">Delivery History</h1>
                <p class="page-subtitle">Review your past deliveries and performance</p>
            </div>
            <div class="card-container">
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h3>Past Deliveries</h3>
                    <p>Review your completed deliveries and performance metrics</p>
                </div>
            </div>
        </div>
        <!-- Profile Section -->
        <div class="content-container content-section" id="profile">
            <div class="page-header">
                <h1 class="page-title">User Profile</h1>
                <p class="page-subtitle">Manage your profile and account settings</p>
            </div>

            <div class="card-container">
                <div class="card p-4">
                    <form action="/delivery/profile" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">
                        <div class="row g-3">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    @php
                                        // Prefer staff photo from staff_photos table, fallback to staff->photo or a placeholder
                                        $photoUrl = null;
                                        if (!empty($staffPhotos->path ?? null)) {
                                            $photoUrl = Storage::url($staffPhotos->path);
                                        } elseif (!empty($staff->photo ?? null)) {
                                            $photoUrl = Storage::url($staff->photo);
                                        } else {
                                            $photoUrl = asset('images/default-avatar.png');
                                        }
                                    @endphp

                                    <div class="profile-preview" style="width:160px;margin:0 auto">
                                        <img id="profilePreviewImg" src="{{ $photoUrl }}" alt="profile"
                                            style="width:160px;height:160px;object-fit:cover;border-radius:12px;border:1px solid rgba(15,23,42,0.06)">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="btn btn-outline-secondary btn-sm">
                                        Change photo
                                        <input type="file" name="photo" id="photoInput" accept="image/*"
                                            style="display:none">
                                    </label>
                                    <button type="button" id="removePhotoBtn"
                                        class="btn btn-link btn-sm text-danger">Remove</button>
                                </div>
                                <p class="muted small">PNG, JPG up to 2MB</p>
                            </div>

                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full name</label>
                                        <input name="name" type="text" class="form-control"
                                            value="{{ old('name', $staff->name ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input name="email" type="email" class="form-control"
                                            value="{{ old('email', $staff->email ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input name="phone" type="text" class="form-control"
                                            value="{{ old('phone', $staff->phone ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control"
                                            value="{{ ucfirst($staff->role ?? 'staff') }}" readonly>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Bio / Notes</label>
                                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $staff->bio ?? '') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Password (leave blank to keep)</label>
                                        <input name="password" type="password" class="form-control">
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                                        <div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                            <a href="#" class="btn btn-outline-secondary ms-2"
                                                id="cancelProfile">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="content-container content-section" id="settings">
            <div class="page-header">
                <h1 class="page-title">Settings</h1>
                <p class="page-subtitle">Configure your application preferences</p>
            </div>
            <div class="card-container">
                <div class="empty-state">
                    <i class="fas fa-cog"></i>
                    <h3>Application Settings</h3>
                    <p>Customize your experience and preferences</p>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Close mobile sidebar on mobile
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            }
        });

        // Profile Dropdown Toggle
        const userProfile = document.getElementById('userProfile');

        userProfile.addEventListener('click', (e) => {
            e.stopPropagation();
            userProfile.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userProfile.contains(e.target)) {
                userProfile.classList.remove('active');
            }
        });

        // Handle dropdown navigation items
        document.querySelectorAll('.dropdown-item[data-section]').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.getAttribute('data-section');

                // Close dropdown
                userProfile.classList.remove('active');

                // Navigate to section
                const targetLink = document.querySelector(`.nav-link[data-section="${section}"]`);
                if (targetLink) {
                    targetLink.click();
                }
            });
        });

        // Navigation
        const navLinks = document.querySelectorAll('.nav-link[data-section]');
        const contentSections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetSection = link.getAttribute('data-section');

                // Update active nav link
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // Show target section
                contentSections.forEach(section => {
                    section.classList.remove('active');
                });

                const targetElement = document.getElementById(targetSection);
                if (targetElement) {
                    targetElement.classList.add('active');
                }

                // Close mobile sidebar
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                }
            });
        });

        // Start Delivery
        function startDelivery(btn) {
            const item = btn.closest('.delivery-item');
            const badge = item.querySelector('.status-badge');
            badge.className = 'status-badge progress';
            badge.textContent = 'In Progress';

            const actions = item.querySelector('.delivery-actions');
            actions.innerHTML = `
                <button class="btn-action btn-outline-action">
                    <i class="fas fa-map"></i> View Route
                </button>
                <button class="btn-action btn-success-action" onclick="completeDelivery(this)">
                    <i class="fas fa-check"></i> Mark Complete
                </button>
            `;

            // Update stats with null checks
            const pendingCount = document.getElementById('pendingCount');
            const progressCount = document.getElementById('progressCount');

            if (pendingCount) {
                pendingCount.textContent = Math.max(0, parseInt(pendingCount.textContent) - 1);
            }
            if (progressCount) {
                progressCount.textContent = parseInt(progressCount.textContent) + 1;
            }

            showNotification('Delivery started successfully!', 'success');
        }

        function startDelivery(btn, orderId = null) {
            // Support both card-list and table rows
            let row = null;
            if (btn) {
                row = btn.closest('.delivery-item');
            }
            if (!row && btn) {
                row = btn.closest('tr');
            }

            if (row) {
                // card style
                const badge = row.querySelector('.status-badge') || row.querySelector('.badge-status');
                if (badge) {
                    badge.className = 'status-badge progress';
                    badge.textContent = 'In Progress';
                }

                const actions = row.querySelector('.delivery-actions') || row.querySelector('td:last-child');
                if (actions) {
                    actions.innerHTML = `
                        <button class="btn-action btn-outline-action">
                            <i class="fas fa-map"></i> View Route
                        </button>
                        <button class="btn-action btn-success-action" onclick="completeDelivery(this)">
                            <i class="fas fa-check"></i> Mark Complete
                        </button>
                    `;
                }
            }

            // Update stats with null checks
            const pendingCount = document.getElementById('pendingCount');
            const progressCount = document.getElementById('progressCount');

            if (pendingCount) {
                pendingCount.textContent = Math.max(0, parseInt(pendingCount.textContent) - 1);
            }
            if (progressCount) {
                progressCount.textContent = parseInt(progressCount.textContent) + 1;
            }

            showNotification('Delivery started successfully!', 'success');
        }

        // Complete Delivery
        function completeDelivery(btn) {
            const item = btn.closest('.delivery-item');
            const badge = item.querySelector('.status-badge');
            badge.className = 'status-badge completed';
            badge.textContent = 'Completed';

            const actions = item.querySelector('.delivery-actions');
            actions.innerHTML = `
                <button class="btn-action btn-outline-action" disabled>
                    <i class="fas fa-check-circle"></i> Completed
                </button>
            `;

            // Update stats
            const progressCount = document.getElementById('progressCount');
            const completedCount = document.getElementById('completedCount');
            const earningsEl = document.getElementById('earnings');

            if (progressCount) {
                progressCount.textContent = Math.max(0, parseInt(progressCount.textContent) - 1);
            }
            if (completedCount) {
                completedCount.textContent = parseInt(completedCount.textContent) + 1;
            }

            // Add $25 to earnings (example)
            if (earningsEl) {
                const currentEarnings = parseInt(earningsEl.textContent.replace('$', ''));
                earningsEl.textContent = '$' + (currentEarnings + 25);
            }

            showNotification('Delivery completed! +$25 earned', 'success');

            // Remove item after animation
            setTimeout(() => {
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(100%)';
                setTimeout(() => item.remove(), 300);
            }, 2000);
        }

        function completeDelivery(btn, orderId = null) {
            let row = null;
            if (btn) row = btn.closest('.delivery-item');
            if (!row && btn) row = btn.closest('tr');

            if (row) {
                const badge = row.querySelector('.status-badge') || row.querySelector('.badge-status');
                if (badge) {
                    badge.className = 'status-badge completed';
                    badge.textContent = 'Completed';
                }

                const actions = row.querySelector('.delivery-actions') || row.querySelector('td:last-child');
                if (actions) {
                    actions.innerHTML = `
                        <button class="btn-action btn-outline-action" disabled>
                            <i class="fas fa-check-circle"></i> Completed
                        </button>
                    `;
                }

                // If it's a table row, optionally remove or mark it
                if (row.tagName === 'TR') {
                    row.classList.add('table-success');
                } else {
                    // remove card after animation
                    setTimeout(() => {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(100%)';
                        setTimeout(() => row.remove(), 300);
                    }, 800);
                }
            }

            // Update stats with null checks
            const progressCount = document.getElementById('progressCount');
            const completedCount = document.getElementById('completedCount');
            const earningsEl = document.getElementById('earnings');

            if (progressCount) {
                progressCount.textContent = Math.max(0, parseInt(progressCount.textContent) - 1);
            }
            if (completedCount) {
                completedCount.textContent = parseInt(completedCount.textContent) + 1;
            }

            // Add $25 to earnings (example)
            if (earningsEl) {
                const currentEarnings = parseInt(earningsEl.textContent.replace('$', '')) || 0;
                earningsEl.textContent = '$' + (currentEarnings + 25);
            }

            showNotification('Delivery completed! +$25 earned', 'success');
        }

        function viewOrder(orderId) {
            if (!orderId) return;
            // Redirect to a view page; adjust path if you have a named route
            window.location.href = '/orders/' + orderId;
        }

        // Show Notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                right: 32px;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                font-weight: 600;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Logout
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showNotification('Logging out...', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            }
        }

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
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
        `;
        document.head.appendChild(style);

        // Filter Tabs
        const filterTabs = document.querySelectorAll('.filter-tab');
        filterTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                filterTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                // Add filter logic here
            });
        });

        // Real-time clock (optional)
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString();
            // You can display this somewhere if needed
        }
        setInterval(updateClock, 1000);

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const savedTheme = localStorage.getItem('theme') || 'light';

        // Apply saved theme on page load
        document.documentElement.setAttribute('data-theme', savedTheme);

        // Toggle theme on button click
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';

                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);

                showNotification(`${newTheme === 'dark' ? 'Dark' : 'Light'} mode activated`, 'success');
            });
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
                    // set to placeholder and mark remove flag
                    previewImg.src = '{{ asset('images/default-avatar.png') }}';
                    if (photoInput) photoInput.value = '';
                    if (removeInput) removeInput.value = '1';
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    // restore original values
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
