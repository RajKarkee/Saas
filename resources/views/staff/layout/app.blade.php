<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Staff Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            transform: translateX(-250px);
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 10px 20px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .navbar {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .navbar.expanded {
            margin-left: 0;
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content,
            .navbar {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .overlay.show {
                display: block;
            }
        }

        .card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    @include('staff.layout.sidebar')
    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Top Navigation Bar -->
    @include('staff.layout.navbar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4">
            @yield('content')
            {{-- <h1 class="mb-4">Dashboard Overview</h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-users"></i> Total Users</h5>
                            <p class="card-text">1,234</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-line"></i> Revenue</h5>
                            <p class="card-text">$12,345</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-tasks"></i> Tasks</h5>
                            <p class="card-text">56 Pending</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add more dashboard content here -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Recent Activity
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">User John Doe logged in</li>
                                <li class="list-group-item">Report generated for Q1</li>
                                <li class="list-group-item">Settings updated</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar on mobile
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('show');
                $('#overlay').toggleClass('show');
            });

            // Close sidebar when overlay is clicked
            $('#overlay').click(function() {
                $('#sidebar').removeClass('show');
                $('#overlay').removeClass('show');
            });

            // Toggle sidebar on desktop (optional, for collapsing)
            // You can add a button in the navbar for desktop collapse if needed
        });
    </script>
</body>

</html>
