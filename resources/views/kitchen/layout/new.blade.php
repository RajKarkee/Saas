<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2d3436;
            --secondary: #636e72;
            --accent-orange: #ff6348;
            --accent-green: #00b894;
            --accent-yellow: #fdcb6e;
            --accent-blue: #0984e3;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --text-light: #b2bec3;
            --card-bg: #ffffff;
            --border-color: #e8e8e8;
            --bg-light: #f8f9fa;
            --shadow: rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #ffffff;
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 99, 72, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 184, 148, 0.03) 0%, transparent 50%),
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0, 0, 0, 0.01) 2px, rgba(0, 0, 0, 0.01) 4px);
            pointer-events: none;
            z-index: 0;
        }

        /* Header */
        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--border-color);
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.6s ease-out;
            box-shadow: 0 2px 12px var(--shadow);
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo-section h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-yellow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 8px rgba(255, 99, 72, 0.1);
        }

        .stats-pills {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .stat-pill {
            background: var(--bg-light);
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .stat-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--shadow);
        }

        .stat-pill i {
            font-size: 1.1rem;
        }

        .stat-pill.pending {
            color: var(--accent-yellow);
            border-color: rgba(253, 203, 110, 0.3);
            background: rgba(253, 203, 110, 0.1);
        }

        .stat-pill.cooking {
            color: var(--accent-orange);
            border-color: rgba(255, 99, 72, 0.3);
            background: rgba(255, 99, 72, 0.1);
        }

        .stat-pill.ready {
            color: var(--accent-green);
            border-color: rgba(0, 184, 148, 0.3);
            background: rgba(0, 184, 148, 0.1);
        }

        /* Filter Tabs */
        .filter-tabs {
            background: var(--bg-light);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 0.5rem;
            display: inline-flex;
            gap: 0.5rem;
            margin: 2rem 0;
            animation: fadeIn 0.8s ease-out 0.2s both;
            box-shadow: 0 4px 12px var(--shadow);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Manrope', sans-serif;
        }

        .filter-btn:hover {
            color: var(--text-primary);
            background: rgba(255, 99, 72, 0.1);
        }

        .filter-btn.active {
            background: var(--accent-orange);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 99, 72, 0.3);
        }

        /* Orders Grid */
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        /* Order Card */
        .order-card {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: scaleIn 0.5s ease-out both;
            box-shadow: 0 2px 8px var(--shadow);
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--accent-orange);
            transition: width 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent-orange);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        }

        .order-card:hover::before {
            width: 100%;
            opacity: 0.03;
        }

        .order-card.status-pending::before {
            background: var(--accent-yellow);
        }

        .order-card.status-cooking::before {
            background: var(--accent-orange);
        }

        .order-card.status-ready::before {
            background: var(--accent-green);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .order-number {
            font-family: 'DM Mono', monospace;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-primary);
        }

        .order-time {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-family: 'DM Mono', monospace;
        }

        .order-items {
            margin: 1rem 0;
            max-height: 200px;
            overflow-y: auto;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-primary);
        }

        .item-qty {
            background: var(--bg-light);
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 700;
            font-family: 'DM Mono', monospace;
            color: var(--accent-orange);
            border: 2px solid rgba(255, 99, 72, 0.2);
        }

        .order-notes {
            background: rgba(255, 99, 72, 0.08);
            border-left: 4px solid var(--accent-orange);
            padding: 0.85rem;
            margin: 1rem 0;
            border-radius: 8px;
            font-size: 0.875rem;
            font-style: italic;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .order-actions {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .action-btn {
            padding: 0.875rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Manrope', sans-serif;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-view {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            aspect-ratio: 1;
            padding: 0;
        }

        .btn-view:hover {
            background: var(--accent-blue);
            color: white;
            border-color: var(--accent-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(9, 132, 227, 0.25);
        }

        .btn-start {
            background: linear-gradient(135deg, var(--accent-orange), #ff8c5a);
            color: white;
            border: none;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 99, 72, 0.3);
        }

        .btn-ready {
            background: linear-gradient(135deg, var(--accent-green), #55efc4);
            color: white;
            border: none;
        }

        .btn-ready:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 184, 148, 0.3);
        }

        .btn-complete {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-complete:hover {
            background: var(--text-primary);
            color: white;
            border-color: var(--text-primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(45, 52, 54, 0.2);
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .status-badge.pending {
            background: rgba(253, 203, 110, 0.15);
            color: #c89005;
            border: 2px solid rgba(253, 203, 110, 0.4);
        }

        .status-badge.cooking {
            background: rgba(255, 99, 72, 0.15);
            color: #d63031;
            border: 2px solid rgba(255, 99, 72, 0.4);
        }

        .status-badge.ready {
            background: rgba(0, 184, 148, 0.15);
            color: #00826d;
            border: 2px solid rgba(0, 184, 148, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .empty-state p {
            color: var(--text-secondary);
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 5rem;
            right: 1rem;
            z-index: 9999;
        }

        .custom-toast {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1rem 1.5rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.4s ease-out;
            color: var(--text-primary);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .custom-toast i {
            font-size: 1.5rem;
        }

        .custom-toast.success {
            border-left: 4px solid var(--accent-green);
        }

        .custom-toast.info {
            border-left: 4px solid var(--accent-orange);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-yellow));
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-title {
            font-weight: 800;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-body {
            padding: 2rem;
            color: var(--text-primary);
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-section h6 {
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-section h6 i {
            color: var(--accent-orange);
        }

        .detail-item {
            background: var(--bg-light);
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            border: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            border-color: var(--accent-orange);
            transform: translateX(4px);
        }

        .detail-item-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .detail-item-qty {
            background: white;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-weight: 700;
            color: var(--accent-orange);
            font-family: 'DM Mono', monospace;
            border: 2px solid rgba(255, 99, 72, 0.2);
        }

        .detail-info {
            background: var(--bg-light);
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border-left: 4px solid var(--accent-blue);
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .detail-info strong {
            color: var(--text-primary);
            display: block;
            margin-bottom: 0.25rem;
        }

        .modal-footer {
            border: none;
            padding: 1.5rem 2rem;
            background: var(--bg-light);
        }

        .modal-footer .btn {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .modal-backdrop.show {
            opacity: 0.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .logo-section h1 {
                font-size: 1.5rem;
            }

            .stats-pills {
                width: 100%;
            }

            .stat-pill {
                flex: 1;
                justify-content: center;
            }

            .filter-tabs {
                width: 100%;
                justify-content: center;
            }

            .filter-btn {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }

            .orders-grid {
                grid-template-columns: 1fr;
            }

            .order-number {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-header {
                padding: 1rem 0;
            }

            .filter-tabs {
                flex-direction: column;
                width: 100%;
            }

            .filter-btn {
                width: 100%;
                text-align: center;
            }

            .order-actions {
                grid-template-columns: 1fr;
            }

            .action-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <h1><i class="bi bi-fire"></i> KITCHEN</h1>
                </div>
                <div class="stats-pills">
                    <div class="stat-pill pending">
                        <i class="bi bi-clock-history"></i>
                        <span id="pendingCount">{{ $stats['pending'] }}</span> Pending
                    </div>
                    <div class="stat-pill cooking">
                        <i class="bi bi-fire"></i>
                        <span id="cookingCount">{{ $stats['cooking'] }}</span> Cooking
                    </div>
                    <div class="stat-pill ready">
                        <i class="bi bi-check-circle"></i>
                        <span id="readyCount">{{ $stats['ready'] }}</span> Ready
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container" style="position: relative; z-index: 1; padding: 2rem 15px;">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-btn active" data-filter="all">All Orders</button>
            <button class="filter-btn" data-filter="pending">Pending</button>
            <button class="filter-btn" data-filter="cooking">Cooking</button>
            <button class="filter-btn" data-filter="ready">Ready</button>
        </div>

        <!-- Orders Grid -->
        <div class="orders-grid" id="ordersGrid">
            @forelse($orders as $order)
                <div class="order-card status-{{ $order->status }}" data-order-id="{{ $order->id }}"
                    data-status="{{ $order->status }}">
                    <span class="status-badge {{ $order->status }}">{{ $order->status }}</span>
                    <div class="order-header">
                        <div class="order-number">{{ $order->quantity }}</div>
                        <div class="order-time"><i class="bi bi-clock"></i> {{ $order->time_ago }}</div>
                    </div>
                    <div class="order-items">
                        @foreach ($order->items as $item)
                            <div class="order-item">
                                <span class="item-name">{{ $item->item_name }}</span>
                                <span class="item-qty">×{{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if ($order->notes)
                        <div class="order-notes">
                            <i class="bi bi-info-circle"></i> {{ $order->notes }}
                        </div>
                    @endif
                    <div class="order-actions">
                        <button class="action-btn btn-view" onclick="viewOrderDetails({{ $order->id }})"
                            title="View Details">
                            <i class="bi bi-eye-fill" style="font-size: 1.2rem;"></i>
                        </button>
                        @if ($order->status === 'pending')
                            <button class="action-btn btn-start"
                                onclick="updateOrderStatus({{ $order->id }}, 'cooking')">
                                <i class="bi bi-play-fill"></i> Start Cooking
                            </button>
                        @elseif($order->status === 'cooking')
                            <button class="action-btn btn-ready"
                                onclick="updateOrderStatus({{ $order->id }}, 'ready')">
                                <i class="bi bi-check2"></i> Mark as Ready
                            </button>
                        @elseif($order->status === 'ready')
                            <button class="action-btn btn-complete" onclick="completeOrder({{ $order->id }})">
                                <i class="bi bi-box-arrow-right"></i> Complete Order
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No orders found</h3>
                    <p>New orders will appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Empty State (hidden by default) -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="bi bi-inbox"></i>
            <h3>No orders found</h3>
            <p>New orders will appear here</p>
        </div>
    </main>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">
                        <i class="bi bi-receipt"></i>
                        <span id="modalOrderNumber">Order Details</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Order Info -->
                    <div class="detail-section">
                        <h6><i class="bi bi-info-circle"></i> Order Information</h6>
                        <div class="detail-info">
                            <strong>Order Number:</strong>
                            <span id="modalOrderNum"></span>
                        </div>
                        <div class="detail-info" style="margin-top: 0.75rem;">
                            <strong>Time Placed:</strong>
                            <span id="modalOrderTime"></span>
                        </div>
                        <div class="detail-info" style="margin-top: 0.75rem;">
                            <strong>Status:</strong>
                            <span id="modalOrderStatus"
                                style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 700; font-size: 0.875rem;"></span>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="detail-section">
                        <h6><i class="bi bi-basket"></i> Order Items</h6>
                        <div id="modalOrderItems"></div>
                    </div>

                    <!-- Special Notes -->
                    <div class="detail-section" id="modalNotesSection">
                        <h6><i class="bi bi-chat-left-text"></i> Special Instructions</h6>
                        <div class="detail-info" style="border-left-color: var(--accent-orange); font-style: italic;">
                            <span id="modalOrderNotes"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: var(--text-secondary); border: none;">Close</button>
                    <button type="button" class="btn" id="modalActionBtn"
                        style="background: var(--accent-orange); color: white; border: none;">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let currentFilter = 'all';

        // Initialize dashboard
        $(document).ready(function() {
            // Bootstrap modal instance once
            try {
                window.kitchenOrderModal = new bootstrap.Modal(document.getElementById('orderModal'));
            } catch (e) {
                console.warn('Bootstrap modal init failed', e);
            }
            // Add staggered animation delay
            setTimeout(() => {
                $('.order-card').each(function(index) {
                    $(this).css('animation-delay', (index * 0.1) + 's');
                });
            }, 100);

            // Auto-refresh orders every 30 seconds
            setInterval(refreshOrders, 30000);
        });

        // Filter buttons
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            filterOrders();
        });

        // Filter orders based on current filter
        function filterOrders() {
            const $cards = $('.order-card');
            let visibleCount = 0;

            $cards.each(function() {
                const status = $(this).data('status');
                if (currentFilter === 'all' || status === currentFilter) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            // Show/hide empty state
            if (visibleCount === 0) {
                $('#ordersGrid').hide();
                $('#emptyState').show();
            } else {
                $('#emptyState').hide();
                $('#ordersGrid').show();
            }
        }

        // View order details in modal
        function viewOrderDetails(orderId) {
            $.ajax({
                url: `/kitchen/orders/${orderId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        const order = response.order;
                        if (!order) {
                            showToast('No order data', 'error');
                            return;
                        }

                        // Set modal content
                        $('#modalOrderNumber').text(order.order_number);
                        $('#modalOrderNum').text(order.order_number);
                        $('#modalOrderTime').text(order.time_ago);

                        // Set status with color
                        const statusColors = {
                            'pending': 'background: rgba(253, 203, 110, 0.15); color: #c89005; border: 2px solid rgba(253, 203, 110, 0.4);',
                            'cooking': 'background: rgba(255, 99, 72, 0.15); color: #d63031; border: 2px solid rgba(255, 99, 72, 0.4);',
                            'ready': 'background: rgba(0, 184, 148, 0.15); color: #00826d; border: 2px solid rgba(0, 184, 148, 0.4);'
                        };
                        $('#modalOrderStatus').attr('style', statusColors[order.status]).text(order.status
                            .toUpperCase());

                        // Set items
                        let itemsHTML = '';
                        order.items.forEach(item => {
                            itemsHTML += `
                                <div class="detail-item">
                                    <span class="detail-item-name">${item.item_name}</span>
                                    <span class="detail-item-qty">×${item.quantity}</span>
                                </div>
                            `;
                        });
                        $('#modalOrderItems').html(itemsHTML);

                        // Set notes
                        if (order.notes) {
                            $('#modalNotesSection').show();
                            $('#modalOrderNotes').text(order.notes);
                        } else {
                            $('#modalNotesSection').hide();
                        }

                        // Set action button
                        const actionBtn = $('#modalActionBtn');
                        actionBtn.off('click'); // Remove previous click handlers

                        if (order.status === 'pending') {
                            actionBtn.text('Start Cooking').css('background', 'var(--accent-orange)');
                            actionBtn.on('click', function() {
                                updateOrderStatus(orderId, 'cooking');
                                bootstrap.Modal.getInstance($('#orderModal')[0]).hide();
                            });
                        } else if (order.status === 'cooking') {
                            actionBtn.text('Mark as Ready').css('background', 'var(--accent-green)');
                            actionBtn.on('click', function() {
                                updateOrderStatus(orderId, 'ready');
                                bootstrap.Modal.getInstance($('#orderModal')[0]).hide();
                            });
                        } else if (order.status === 'ready') {
                            actionBtn.text('Complete Order').css('background', 'var(--text-primary)');
                            actionBtn.on('click', function() {
                                completeOrder(orderId);
                                bootstrap.Modal.getInstance($('#orderModal')[0]).hide();
                            });
                        }

                        // Show modal
                        try {
                            if (window.kitchenOrderModal) {
                                window.kitchenOrderModal.show();
                            } else if (window.bootstrap && bootstrap.Modal) {
                                new bootstrap.Modal(document.getElementById('orderModal')).show();
                            } else {
                                // Fallback: force display
                                const el = document.getElementById('orderModal');
                                el.style.display = 'block';
                                el.classList.add('show');
                            }
                        } catch (e) {
                            console.error('Failed to show modal', e);
                            showToast('Unable to open order modal', 'error');
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Order details error', xhr?.status, xhr?.responseText);
                    showToast('Failed to load order details', 'error');
                }
            });
        }

        function updateOrderStatus(orderId, newStatus) {
            $.ajax({
                url: `/kitchen/orders/${orderId}/status`,
                type: 'POST',
                data: {
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');

                        // Update the order card
                        const $card = $(`.order-card[data-order-id="${orderId}"]`);
                        $card.removeClass('status-pending status-cooking status-ready')
                            .addClass(`status-${newStatus}`)
                            .attr('data-status', newStatus);

                        // Update status badge
                        $card.find('.status-badge').removeClass('pending cooking ready')
                            .addClass(newStatus)
                            .text(newStatus);

                        // Update action button
                        updateActionButton($card, newStatus);

                        // Refresh stats
                        refreshStats();

                        // Re-apply filter
                        filterOrders();
                    }
                },
                error: function(xhr) {
                    showToast('Failed to update order status', 'error');
                }
            });
        }

        // Complete order
        function completeOrder(orderId) {
            $.ajax({
                url: `/kitchen/orders/${orderId}/complete`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');

                        // Animate out
                        const $card = $(`.order-card[data-order-id="${orderId}"]`);
                        $card.css('animation', 'scaleOut 0.4s ease-out forwards');

                        setTimeout(() => {
                            $card.remove();
                            refreshStats();
                            filterOrders();
                        }, 400);
                    }
                },
                error: function(xhr) {
                    showToast('Failed to complete order', 'error');
                }
            });
        }

        // Update action button based on status
        function updateActionButton($card, status) {
            const $actions = $card.find('.order-actions');
            let buttonHTML = '';
            const orderId = $card.data('order-id');

            if (status === 'pending') {
                buttonHTML = `
                    <button class="action-btn btn-start" onclick="updateOrderStatus(${orderId}, 'cooking')">
                        <i class="bi bi-play-fill"></i> Start Cooking
                    </button>
                `;
            } else if (status === 'cooking') {
                buttonHTML = `
                    <button class="action-btn btn-ready" onclick="updateOrderStatus(${orderId}, 'ready')">
                        <i class="bi bi-check2"></i> Mark as Ready
                    </button>
                `;
            } else if (status === 'ready') {
                buttonHTML = `
                    <button class="action-btn btn-complete" onclick="completeOrder(${orderId})">
                        <i class="bi bi-box-arrow-right"></i> Complete Order
                    </button>
                `;
            }

            // Keep view button and update action button
            $actions.html(`
                <button class="action-btn btn-view" onclick="viewOrderDetails(${orderId})" title="View Details">
                    <i class="bi bi-eye-fill" style="font-size: 1.2rem;"></i>
                </button>
                ${buttonHTML}
            `);
        }

        // Refresh stats
        function refreshStats() {
            const pending = $('.order-card[data-status="pending"]').length;
            const cooking = $('.order-card[data-status="cooking"]').length;
            const ready = $('.order-card[data-status="ready"]').length;

            $('#pendingCount').text(pending);
            $('#cookingCount').text(cooking);
            $('#readyCount').text(ready);
        }

        // Refresh orders from backend
        function refreshOrders() {
            $.ajax({
                url: '/kitchen/orders/all',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Update stats
                        $('#pendingCount').text(response.stats.pending);
                        $('#cookingCount').text(response.stats.cooking);
                        $('#readyCount').text(response.stats.ready);

                        // You can optionally reload the entire grid here
                        // For now, we'll just update stats
                    }
                }
            });
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const icon = type === 'success' ? 'check-circle-fill' : 'info-circle-fill';
            const iconColor = type === 'success' ? 'var(--accent-green)' : 'var(--accent-orange)';

            const toast = $(`
                <div class="custom-toast ${type}">
                    <i class="bi bi-${icon}" style="color: ${iconColor}; font-size: 1.5rem;"></i>
                    <span style="color: var(--text-primary); font-weight: 600;">${message}</span>
                </div>
            `);

            $('#toastContainer').append(toast);

            setTimeout(() => {
                toast.css('animation', 'slideInRight 0.4s ease-out reverse');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        // Scale out animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes scaleOut {
                to {
                    opacity: 0;
                    transform: scale(0.8);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
