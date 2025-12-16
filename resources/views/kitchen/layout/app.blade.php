<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #ecf0f1;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .order-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .order-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .order-card.status-pending {
            border-left-color: var(--warning-color);
        }

        .order-card.status-cooking {
            border-left-color: #3498db;
        }

        .order-card.status-ready {
            border-left-color: var(--success-color);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .order-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .order-time {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-cooking {
            background-color: #cfe2ff;
            color: #084298;
        }

        .status-ready {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .order-items {
            margin: 15px 0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .item-quantity {
            background-color: var(--primary-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-left: 10px;
        }

        .order-notes {
            background-color: #fffbea;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 3px solid var(--warning-color);
        }

        .order-notes strong {
            color: var(--warning-color);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 120px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-view {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-view:hover {
            background-color: #1a252f;
            transform: scale(1.05);
        }

        .btn-start {
            background-color: #3498db;
            color: white;
            border: none;
        }

        .btn-start:hover {
            background-color: #2980b9;
        }

        .btn-ready {
            background-color: var(--success-color);
            color: white;
            border: none;
        }

        .btn-ready:hover {
            background-color: #229954;
        }

        .btn-complete {
            background-color: #95a5a6;
            color: white;
            border: none;
        }

        .btn-complete:hover {
            background-color: #7f8c8d;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-fire"></i> Kitchen Dashboard
            </span>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">
                    <i class="bi bi-person-circle"></i> {{ auth('staff')->user()->name ?? 'Chef' }}
                </span>
                <form action="{{ route('restaurant.kitchen.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card bg-warning bg-opacity-10">
                    <h3 class="text-warning mb-2">
                        <i class="bi bi-clock-history"></i> {{ $stats['pending'] ?? 0 }}
                    </h3>
                    <p class="mb-0 text-muted">Pending Orders</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card bg-warning bg-opacity-10">
                    <h3 class="text-warning mb-2">
                        <i class="bi bi-clock-history"></i> {{ $stats['accepted'] ?? 0 }}
                    </h3>
                    <p class="mb-0 text-muted">Accepted Orders</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card bg-info bg-opacity-10">
                    <a href='{{ route('restaurant.kitchen.cooking.all') }}' class="text-decoration-none">
                        <h3 class="text-info mb-2">
                            <i class="bi bi-fire"></i> {{ $stats['cooking'] ?? 0 }}
                        </h3>
                    </a>
                    <p class="mb-0 text-muted">Cooking</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card bg-info bg-opacity-10">
                    <a href="{{ route('restaurant.kitchen.cooked.all') }}" class="text-decoration-none">
                        <h3 class="text-info mb-2">
                            <i class="bi bi-fire"></i> {{ $stats['cooked'] ?? 0 }}
                        </h3>
                    </a>
                    <p class="mb-0 text-muted">Cooked</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card bg-success bg-opacity-10">
                    <h3 class="text-success mb-2">
                        <i class="bi bi-check-circle"></i> {{ $stats['completed'] ?? 0 }}
                    </h3>
                    <p class="mb-0 text-muted">Completed</p>
                </div>
            </div>
        </div>

        <!-- Orders Grid -->
        <div class="row" id="ordersContainer">
            @forelse($orders ?? [] as $order)
                <div class="col-md-6 col-lg-4">
                    <div class="order-card status-{{ $order->status }}">
                        <div class="order-header">
                            <div>
                                <div class="order-number">#{{ $order->id }}</div>
                                <div class="order-time">{{ $order->time_ago ?? 'null' }}</div>
                            </div>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="order-items">
                            @foreach ($order->items ?? [] as $item)
                                <div class="order-item">
                                    <span class="item-name">{{ $item->item_name ?? 'Item' }}</span>
                                    <span class="item-quantity">×{{ $item->quantity ?? 1 }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if ($order->notes)
                            <div class="order-notes">
                                <strong><i class="bi bi-sticky"></i> Note:</strong> {{ $order->notes }}
                            </div>
                        @endif

                        <div class="action-buttons">
                            <button class="btn btn-action btn-view" id="viewOrderBtn"
                                data-route-template="{{ url('/restaurant/kitchen/orders/:id') }}"
                                onclick="viewOrderDetails({{ $order->id }})">
                                <i class="bi bi-eye"></i> View
                            </button>

                            @if ($order->status === 'pending')
                                <button class="btn btn-action btn-start"
                                    onclick="updateOrderStatus({{ $order->id }}, 'cooking')">
                                    <i class="bi bi-play-circle"></i> Start
                                </button>
                            @elseif($order->status === 'cooking')
                                <button class="btn btn-action btn-ready"
                                    onclick="updateOrderStatus({{ $order->id }}, 'ready')">
                                    <i class="bi bi-check-circle"></i> Ready
                                </button>
                            @elseif($order->status === 'ready')
                                <button class="btn btn-action btn-complete"
                                    onclick="completeOrder({{ $order->id }})">
                                    <i class="bi bi-check2-all"></i> Complete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No Orders</h4>
                        <p>All caught up! No orders to prepare right now.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">
                        <i class="bi bi-receipt"></i> Order Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body" id="orderModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                @if (isset($order))
                    <form method ="POST" action='{{ route('kitchen.orders.updateStatus', $order->id) }}'>
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Start cooking</button>
                        </div>
                    </form>
                @endif
                <hr>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="actionToast" class="toast align-items-center text-white border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize modal
        let orderModal;
        $(document).ready(function() {
            orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
        });

        // View Order Details
        function viewOrderDetails(orderId) {
            $('#orderModalBody').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);

            orderModal.show();
            const template = $('#viewOrderBtn').data('route-template');
            const url = template.replace(':id', orderId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const order = response.order;
                        let itemsHtml = '';

                        order.items.forEach(item => {
                            itemsHtml += `
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>${item.item_name}</strong>
                                        <span class="badge bg-secondary ms-2">×${item.quantity}</span>
                                    </div>
                                </div>
                            `;
                        });

                        const notesHtml = order.notes ? `
                            <div class="alert alert-warning">
                                <i class="bi bi-sticky"></i> <strong>Notes:</strong> ${order.notes}
                            </div>
                        ` : '';

                        $('#orderModalBody').html(`
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order Number:</strong> #${order.id}</p>
                                    <p><strong>Time:</strong> ${order.time_ago}</p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="badge status-badge status-${order.status} fs-6">
                                        ${order.status.toUpperCase()}
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <h6 class="mb-3"><i class="bi bi-list-ul"></i> Order Items</h6>
                            ${itemsHtml}
                            <hr>
                            ${notesHtml}
                       
                        `);
                    } else {
                        showToast('Failed to load order details', 'danger');
                        orderModal.hide();
                    }
                },
                error: function() {
                    showToast('Error loading order details', 'danger');
                    orderModal.hide();
                }
            });
        }

        // Update Order Status
        function updateOrderStatus(orderId, newStatus) {
            $.ajax({
                url: `/restaurant/kitchen/orders/${orderId}/status`,
                method: 'POST',
                dataType: 'json',
                data: {
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message || 'Status updated successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(response.message || 'Failed to update status', 'danger');
                    }
                },
                error: function() {
                    showToast('Error updating status', 'danger');
                }
            });
        }

        // Complete Order
        function completeOrder(orderId) {
            if (!confirm('Mark this order as complete?')) return;

            $.ajax({
                url: `/restaurant/kitchen/orders/${orderId}/complete`,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast(response.message || 'Order completed successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(response.message || 'Failed to complete order', 'danger');
                    }
                },
                error: function() {
                    showToast('Error completing order', 'danger');
                }
            });
        }

        // Show Toast Notification
        function showToast(message, type = 'info') {
            const toast = document.getElementById('actionToast');
            const toastBody = document.getElementById('toastMessage');

            // Set background color based on type
            const bgColors = {
                'success': 'bg-success',
                'danger': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            };

            toast.className = `toast align-items-center text-white border-0 ${bgColors[type] || bgColors.info}`;
            toastBody.textContent = message;

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>

</html>
