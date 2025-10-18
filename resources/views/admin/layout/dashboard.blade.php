@extends('admin.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">2,345</div>
            <div class="stat-label">Total Users</div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">$45,678</div>
            <div class="stat-label">Revenue</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-value">567</div>
            <div class="stat-label">Orders</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-value">89</div>
            <div class="stat-label">Restaurants</div>
        </div>
    </div>

    <!-- Content Cards -->
    <div class="row">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h5><i class="fas fa-chart-line me-2"></i>Revenue Overview</h5>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Last 3 Months</option>
                    </select>
                </div>
                <div class="content-card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card">
                <div class="content-card-header">
                    <h5><i class="fas fa-tasks me-2"></i>Recent Activity</h5>
                </div>
                <div class="content-card-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="activity-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="activity-icon bg-primary text-white rounded-circle p-2 me-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1"><strong>New user registered</strong></p>
                                <small class="text-muted">John Doe - 2 minutes ago</small>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="activity-icon bg-success text-white rounded-circle p-2 me-3">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1"><strong>New order placed</strong></p>
                                <small class="text-muted">Order #1234 - 15 minutes ago</small>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="activity-icon bg-warning text-white rounded-circle p-2 me-3">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1"><strong>Restaurant pending approval</strong></p>
                                <small class="text-muted">Pizza Palace - 1 hour ago</small>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="activity-icon bg-danger text-white rounded-circle p-2 me-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1"><strong>Payment failed</strong></p>
                                <small class="text-muted">Order #1233 - 2 hours ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="content-card mt-4">
        <div class="content-card-header">
            <h5><i class="fas fa-list me-2"></i>Recent Orders</h5>
            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Restaurant</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-1234</td>
                            <td>John Doe</td>
                            <td>Pizza Palace</td>
                            <td>$45.99</td>
                            <td><span class="badge bg-success">Delivered</span></td>
                            <td>2024-10-14</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#ORD-1233</td>
                            <td>Jane Smith</td>
                            <td>Burger King</td>
                            <td>$32.50</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>2024-10-14</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#ORD-1232</td>
                            <td>Mike Johnson</td>
                            <td>Sushi Express</td>
                            <td>$67.80</td>
                            <td><span class="badge bg-info">Preparing</span></td>
                            <td>2024-10-14</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Revenue Chart
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [1200, 1900, 1500, 2500, 2200, 3000, 2800],
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
