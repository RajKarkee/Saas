<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- searchable --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    @include('admin.layout.sidebar')
    {{-- <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="fas fa-crown brand-icon"></i>
            <span class="brand-text">Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <ul class="list-unstyled">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <!-- Users Dropdown -->
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="users">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Users</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-list"></i>
                                <span class="nav-text">All Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-plus"></i>
                                <span class="nav-text">Add User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-shield"></i>
                                <span class="nav-text">Roles</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Restaurants Dropdown -->
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="restaurants">
                        <i class="fas fa-store"></i>
                        <span class="nav-text">Restaurants</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="{{ route('super_admin.restaurant.index') }}" class="nav-link">
                                <i class="fas fa-list"></i>
                                <span class="nav-text">All Restaurants</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-plus-circle"></i>
                                <span class="nav-text">Add Restaurant</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-clock"></i>
                                <span class="nav-text">Pending Approval</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Orders -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="nav-text">Orders</span>
                    </a>
                </li>

                <!-- Reports Dropdown -->
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="reports">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-dollar-sign"></i>
                                <span class="nav-text">Sales Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span class="nav-text">Customer Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-line"></i>
                                <span class="nav-text">Revenue Report</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Settings Dropdown -->
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="settings">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-sliders-h"></i>
                                <span class="nav-text">General</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-credit-card"></i>
                                <span class="nav-text">Payment Gateway</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-envelope"></i>
                                <span class="nav-text">Email Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Support -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-life-ring"></i>
                        <span class="nav-text">Support</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside> --}}

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Top Navbar -->
        <nav class="admin-navbar">
            <div class="navbar-left">
                <button class="navbar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="navbar-search">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </div>

            <div class="navbar-right">
                <!-- Notifications -->
                <div class="navbar-icon" id="notificationsIcon">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger">3</span>
                </div>

                <!-- Messages -->
                <div class="navbar-icon" id="messagesIcon">
                    <i class="fas fa-envelope"></i>
                    <span class="badge bg-success">5</span>
                </div>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-avatar">
                        <span>AD</span>
                    </div>
                    <div class="profile-menu" id="profileMenu">
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Activity Log</span>
                        </a>
                        <a href="#" class="profile-menu-item"
                            onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) { document.getElementById('logout-form').submit(); }">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="#" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="admin-main">
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <!-- Custom Admin Scripts -->

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // ============ Sidebar Toggle ============
        $('#sidebarToggle').on('click', function() {
            const sidebar = $('#adminSidebar');
            const isMobile = $(window).width() < 768;

            if (isMobile) {
                sidebar.toggleClass('mobile-show');
                $('#sidebarOverlay').toggleClass('show');
            } else {
                sidebar.toggleClass('collapsed');
            }
        });

        // Close sidebar when clicking overlay (mobile)
        $('#sidebarOverlay').on('click', function() {
            $('#adminSidebar').removeClass('mobile-show');
            $(this).removeClass('show');
        });

        // Close sidebar on mobile when clicking a link
        $('.sidebar-nav .nav-link').on('click', function() {
            if ($(window).width() < 768 && !$(this).hasClass('nav-dropdown-toggle')) {
                $('#adminSidebar').removeClass('mobile-show');
                $('#sidebarOverlay').removeClass('show');
            }
        });

        // ============ Sidebar Dropdowns ============
        $('.nav-dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            const parent = $(this).closest('.nav-dropdown');
            const allDropdowns = $('.nav-dropdown');

            // Close other dropdowns
            allDropdowns.not(parent).removeClass('open');

            // Toggle current dropdown
            parent.toggleClass('open');
        });

        // ============ Profile Dropdown ============
        $('#profileDropdown').on('click', function(e) {
            e.stopPropagation();
            $('#profileMenu').toggleClass('show');
        });

        // Close profile menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#profileDropdown').length) {
                $('#profileMenu').removeClass('show');
            }
        });

        // ============ Active Link Highlighting ============
        // Get current page URL
        const currentUrl = window.location.href;
        $('.sidebar-nav .nav-link').each(function() {
            const linkUrl = $(this).attr('href');
            if (linkUrl && linkUrl !== '#' && currentUrl.includes(linkUrl)) {
                // Remove active from all links
                $('.sidebar-nav .nav-link').removeClass('active');
                // Add active to current link
                $(this).addClass('active');
                // Open parent dropdown if exists
                $(this).closest('.nav-dropdown').addClass('open');
            }
        });

        // ============ Smooth Scroll ============
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 70
                }, 600);
            }
        });

        // ============ Responsive Sidebar ============
        $(window).on('resize', function() {
            if ($(window).width() >= 768) {
                $('#adminSidebar').removeClass('mobile-show');
                $('#sidebarOverlay').removeClass('show');
            }
        });

        // ============ Notification Click Handler ============
        $('#notificationsIcon').on('click', function() {
            alert('Notifications feature coming soon!');
        });

        $('#messagesIcon').on('click', function() {
            alert('Messages feature coming soon!');
        });
    });
</script>
@stack('scripts')

</html>
