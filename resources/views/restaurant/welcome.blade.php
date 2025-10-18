<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub Admin - Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <!-- Sidebar -->
    @include('restaurant.layout.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        @include('restaurant.layout.navbar')

        @yield('content')

    </div>

    <!-- Add Restaurant Modal -->
    <div class="modal fade" id="addRestaurantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Restaurant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Restaurant Name</label>
                                <input type="text" class="form-control" placeholder="Enter restaurant name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Owner Name</label>
                                <input type="text" class="form-control" placeholder="Enter owner name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Enter email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" placeholder="Enter phone">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" placeholder="Enter address">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" placeholder="Enter city">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cuisine Type</label>
                                <select class="form-select">
                                    <option selected>Select cuisine</option>
                                    <option>American</option>
                                    <option>Italian</option>
                                    <option>Chinese</option>
                                    <option>Mexican</option>
                                    <option>Japanese</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" placeholder="Enter description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom btn-primary-custom">Add Restaurant</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Menu Item Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Restaurant</label>
                            <select class="form-select">
                                <option selected>Select restaurant</option>
                                <option>Burger King</option>
                                <option>Pizza Palace</option>
                                <option>Sushi Express</option>
                                <option>Taco Town</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" placeholder="Enter item name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" placeholder="Enter category">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" placeholder="Enter price" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Availability</label>
                            <select class="form-select">
                                <option selected>Available</option>
                                <option>Out of Stock</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom btn-primary-custom">Add Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Sidebar Toggle
            $('#menuToggle').on('click', function() {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');

                // Mobile view
                if ($(window).width() < 768) {
                    $('#sidebar').toggleClass('mobile-show');
                }
            });

            // Navigation
            // Only intercept nav links that have a `data-page` attribute (single-page UI behavior).
            // Links that point to server routes (no data-page) should be allowed to navigate normally.
            $('.nav-link').on('click', function(e) {
                const page = $(this).data('page');

                // If this link is meant to be a regular href/navigation (no data-page), don't intercept
                if (typeof page === 'undefined') {
                    return; // allow default navigation
                }

                e.preventDefault();

                if (page === 'logout') {
                    if (confirm('Are you sure you want to logout?')) {
                        alert('Logged out successfully!');
                    }
                    return;
                }

                // Update active nav link
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                // Show corresponding page
                $('.page-content').removeClass('active');
                $('#' + page).addClass('active');

                // Close sidebar on mobile
                if ($(window).width() < 768) {
                    $('#sidebar').removeClass('mobile-show');
                }
            });

            // Global Search
            $('#globalSearch').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                const activePage = $('.page-content.active');

                activePage.find('tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Order Status Change
            $('.order-status-select').on('change', function() {
                const status = $(this).val();
                alert('Order status updated to: ' + status);
            });

            // Delete Button
            $('.btn-delete').on('click', function() {
                if (confirm('Are you sure you want to delete this item?')) {
                    $(this).closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });

            // View Button
            $('.btn-view').on('click', function() {
                alert('View details functionality would open here');
            });

            // Edit Button
            $('.btn-edit').on('click', function() {
                alert('Edit functionality would open here');
            });

            // Chart.js Implementation
            const ctx = document.getElementById('revenueChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                        datasets: [{
                            label: 'Revenue',
                            data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000,
                                40000
                            ],
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#4e73df',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return (value / 1000) + 'K';
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() < 768) {
                    if (!$(e.target).closest('#sidebar, #menuToggle').length) {
                        $('#sidebar').removeClass('mobile-show');
                    }
                }
            });

            // Profile dropdown toggle
            $('.profile-dropdown').on('click', function(e) {
                e.stopPropagation();
                $('#profileMenu').toggleClass('show');
            });

            // Close profile menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.profile-dropdown, #profileMenu').length) {
                    $('#profileMenu').removeClass('show');
                }
            });

            // Logout link
            $('#logoutLink').on('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    alert('Logged out successfully!');
                }
            });

            // Form submission handlers
            $('.modal form').on('submit', function(e) {
                e.preventDefault();
                alert('Form submitted successfully!');
                $(this).closest('.modal').modal('hide');
            });
        });
    </script>
</body>

</html>
