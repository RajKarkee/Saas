<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Dashboard')</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
    {{-- <style>
        .profile-modal-overlay {
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            display: none;
            animation: fadeIn 0.2s ease;
        }

        .profile-modal-overlay.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-modal-content {
            background: #ffffff;
            border-radius: 12px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: scaleIn 0.2s ease;
        }

        .profile-modal-header {
            padding: 24px 24px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .profile-modal-close {
            background: transparent;
            border: none;
            color: #6b7280;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-modal-close:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .profile-modal-body {
            padding: 24px;
        }

        .profile-avatar-section {
            display: flex;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .profile-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 600;
            color: #ffffff;
            margin-right: 20px;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .profile-avatar-info h4 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 4px 0;
        }

        .profile-avatar-info p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .profile-details-grid {
            display: grid;
            gap: 16px;
        }

        .profile-detail-row {
            display: flex;
            align-items: flex-start;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .profile-detail-row:hover {
            border-color: #d1d5db;
            background: #f3f4f6;
        }

        .profile-detail-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            margin-right: 14px;
            font-size: 16px;
            border: 1px solid #e5e7eb;
        }

        .profile-detail-content {
            flex: 1;
        }

        .profile-detail-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-detail-value {
            font-size: 15px;
            color: #111827;
            font-weight: 500;
        }

        .profile-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
            background: #f9fafb;
        }

        .profile-btn {
            flex: 1;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .profile-btn-primary {
            background: #3b82f6;
            color: #ffffff;
        }

        .profile-btn-primary:hover {
            background: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .profile-btn-secondary {
            background: #ffffff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .profile-btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .profile-modal-content {
                width: 95%;
                margin: 20px;
            }

            .profile-avatar-section {
                flex-direction: column;
                text-align: center;
            }

            .profile-avatar-large {
                margin: 0 0 16px 0;
            }

            .profile-modal-footer {
                flex-direction: column;
            }
        }
    </style> --}}
    @stack('styles')
</head>

<body>

    @include('admin.layout.sidebar')



    <div class="sidebar-overlay" id="sidebarOverlay"></div>


    <div class="admin-content">

        <nav class="admin-navbar">
            <div class="navbar-left">
                <button class="navbar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                {{-- <div class="navbar-search">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Search...">
                </div> --}}
            </div>

            <div class="navbar-right">

                {{-- <div class="navbar-icon" id="notificationsIcon">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger">3</span>
                </div> --}}


                {{-- <div class="navbar-icon" id="messagesIcon">
                    <i class="fas fa-envelope"></i>
                    <span class="badge bg-success">5</span>
                </div> --}}

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-avatar">
                        <span>{{ $superAdmin->name ?? '' }}</span>
                    </div>
                    <div class="profile-menu" id="profileMenu">
                        <a href="#" class="profile-menu-item-admin" data-modal-target="#profileModal">
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

    <!-- Profile Modal -->
    <div id="profileModal" class="profile-modal-overlay" role="dialog" aria-hidden="true"
        aria-labelledby="profileModalTitle">
        <div class="profile-modal-content" role="document">
            <div class="profile-modal-header">
                <h3 class="profile-modal-title" id="profileModalTitle">Profile Information</h3>
                <button type="button" class="profile-modal-close" id="closeProfileModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="profile-modal-body">
                <div class="profile-avatar-section">
                    <div class="profile-avatar-large">
                        @php
                            $initials = 'AD';
                            if (isset($superAdmin) && !empty($superAdmin->name)) {
                                $parts = explode(' ', trim($superAdmin->name));
                                $initials = strtoupper(
                                    substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''),
                                );
                            }
                        @endphp
                        <span>{{ $initials }}</span>
                    </div>
                    <div class="profile-avatar-info">
                        <h4>{{ $superAdmin->name ?? 'Admin User' }}</h4>
                        <p>Super Administrator</p>
                    </div>
                </div>

                <div class="profile-details-grid">
                    <div class="profile-detail-row">
                        <div class="profile-detail-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="profile-detail-content">
                            <div class="profile-detail-label">Full Name</div>
                            <div class="profile-detail-value">{{ $superAdmin->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="profile-detail-content">
                            <div class="profile-detail-label">Email Address</div>
                            <div class="profile-detail-value">{{ $superAdmin->email ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="profile-detail-content">
                            <div class="profile-detail-label">Role</div>
                            <div class="profile-detail-value">Super Administrator</div>
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="profile-detail-content">
                            <div class="profile-detail-label">Member Since</div>
                            <div class="profile-detail-value">
                                {{ isset($superAdmin->created_at) ? \Carbon\Carbon::parse($superAdmin->created_at)->format('F d, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-modal-footer">
                <button type="button" class="profile-btn profile-btn-primary">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </button>
                <button type="button" class="profile-btn profile-btn-secondary" id="closeProfileModalBtn">
                    <i class="fas fa-times"></i>
                    <span>Close</span>
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
        $(document).ready(function() {


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


            $('#sidebarOverlay').on('click', function() {
                $('#adminSidebar').removeClass('mobile-show');
                $(this).removeClass('show');
            });


            $('.sidebar-nav .nav-link').on('click', function() {
                if ($(window).width() < 768 && !$(this).hasClass('nav-dropdown-toggle')) {
                    $('#adminSidebar').removeClass('mobile-show');
                    $('#sidebarOverlay').removeClass('show');
                }
            });

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
            $('.profile-menu-item-admin').on('click', function(e) {
                e.preventDefault();
                $('#profileModal').fadeIn();
            });
            $('#closeProfileModal-admin').on('click', function() {
                $('#profileModal').fadeOut();
            });

            $(window).click(function(e) {
                if ($(e.target).is('#profileModal')) {
                    $('#profileModal').fadeOut();
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('.modal:visible').hide().attr('aria-hidden', 'true');
                }
            });



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


            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 70
                    }, 600);
                }
            });


            $(window).on('resize', function() {
                if ($(window).width() >= 768) {
                    $('#adminSidebar').removeClass('mobile-show');
                    $('#sidebarOverlay').removeClass('show');
                }
            });


            // $('#notificationsIcon').on('click', function() {
            //     alert('Notifications feature coming soon!');
            // });

            // $('#messagesIcon').on('click', function() {
            //     alert('Messages feature coming soon!');
            // });


            $(document).on('click', '[data-modal-target="#profileModal"]', function(e) {
                e.preventDefault();
                $('#profileModal').addClass('show').attr('aria-hidden', 'false');
            });


            $('#closeProfileModal, #closeProfileModalBtn').on('click', function() {
                $('#profileModal').removeClass('show').attr('aria-hidden', 'true');
            });


            $(document).on('click', '#profileModal', function(e) {
                if (e.target === this) {
                    $(this).removeClass('show').attr('aria-hidden', 'true');
                }
            });


            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('#profileModal.show').removeClass('show').attr('aria-hidden', 'true');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
