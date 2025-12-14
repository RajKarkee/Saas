@extends('delivery.layout.app')
@section('content')
    <style>
        .notify {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2d3748;
            color: #fff;
            padding: 12px 18px;
            border-radius: 6px;
            z-index: 9999;
        }
    </style>
    <div class="content-container content-section active" id="dashboard">
        <span id="deliveryBadge" class="badge"
            style="display:none; position:fixed; top:80px; right:20px; z-index:1000;">0</span>
        <audio id="notifySound">
            <source src="/sounds/notify.mp3" type="audio/mpeg">
        </audio>
        <div class="page-header">
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening with your deliveries today.</p>
        </div>

        {{-- <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>

            </div>
            <div class="stat-value" id="pendingCount">8</div>
            <div class="stat-label">Pending Deliveries</div>
        </div>

        <div class="stat-card progress">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 8%
                </div>
            </div>
            <div class="stat-value" id="progressCount">3</div>
            <div class="stat-label">In Progress</div>
        </div>

        <div class="stat-card completed">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 24%
                </div>
            </div>
            <div class="stat-value" id="completedCount">45</div>
            <div class="stat-label">Completed Today</div>
        </div>

        <div class="stat-card earnings">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 18%
                </div>
            </div>
            <div class="stat-value" id="earnings">$340</div>
            <div class="stat-label">Today's Earnings</div>
        </div>
    </div> --}}

        <div class="card-container">
            <div class="card-header-section">
                <h2 class="card-title">Active Deliveries</h2>
                {{-- <div class="filter-tabs">
                    <button class="filter-tab active">All</button>
                    <button class="filter-tab">Urgent</button>
                    <button class="filter-tab">Nearby</button>
                </div> --}}
            </div>
            <div class="deliveryList">

            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="deliveryOrdersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer</th>

                            <th>ETA / Delivery Time</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="deliveryTableBody">
                        @foreach ($orders as $index => $order)
                            <tr data-order-id="{{ $order->id }}">
                                <td data-label="#">{{ $index + 1 }}</td>
                                <td data-label="Order ID">#ORD-{{ $order->id }}</td>
                                <td data-label="Customer">{{ $order->customer_name ?? 'Guest' }}</td>

                                <td data-label="ETA">{{ $order->delivery_time ?? ($order->order_date ?? '—') }}</td>
                                <td data-label="Total">
                                    @php
                                        $displayTotal = null;
                                        if (isset($order->order_total_price)) {
                                            $displayTotal = $order->order_total_price;
                                        } elseif (isset($order->total_amount)) {
                                            $displayTotal = $order->total_amount;
                                        }
                                    @endphp
                                    {{ $displayTotal !== null ? 'RS.' . number_format($displayTotal, 2) : '—' }}
                                </td>
                                <td data-label="Payment">{{ $order->payment_method ?? '—' }}</td>
                                @php
                                    $s = strtolower($order->status ?? '');
                                    $badgeClass = 'bg-secondary text-white';
                                    if ($s === 'pending') {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif (strpos($s, 'transit') !== false || strpos($s, 'in transit') !== false) {
                                        $badgeClass = 'bg-info text-white';
                                    } elseif ($s === 'completed' || strpos($s, 'complete') !== false) {
                                        $badgeClass = 'bg-success text-white';
                                    } elseif ($s === 'cancelled' || $s === 'canceled') {
                                        $badgeClass = 'bg-danger text-white';
                                    }
                                @endphp
                                <td data-label="Status"><span
                                        class="badge status-badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td data-label="Actions">
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="viewOrder(this, {{ $order->id }})"><i class="fas fa-eye"></i></button>
                                    @if ($order->status === 'pending')
                                        <button class="btn btn-sm btn-success"
                                            onclick="startDelivery(this, '{{ route('restaurant.delivery.start', $order->id) }}')"><i
                                                class="fas fa-play"></i></button>
                                    @endif
                                    <button class="btn btn-sm btn-primary"
                                        onclick="completeDelivery(this, {{ $order->id }})"><i
                                            class="fas fa-check"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.routes = {
            pollDeliveries: "{{ route('restaurant.delivery.poll') }}",
            markSeen: "{{ route('restaurant.delivery.markSeen') }}"
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            }
        });
        const Deliverypoller = (function() {
            let lastCheck = new Date().toISOString();
            let unreadCount = 0;
            const $tbody = $('#deliveryTableBody');

            function poll() {
                $.get(window.routes.pollDeliveries, {
                    last_check: lastCheck
                }, function(data) {
                    if (data.length > 0) {
                        data.forEach(addDelivery);
                        lastCheck = data[0].assigned_at || new Date().toISOString();
                        playSound();
                    }
                });
            }

            function addDelivery(delivery) {

                if ($tbody.find(`tr[data-order-id="${delivery.id}"]`).length) {
                    return;
                }
                unreadCount++;
                updateBadge();
                notifyBrowser(delivery);

                const total = (delivery.order_total_price != null) ? delivery.order_total_price : (delivery
                    .total_amount != null ? delivery.total_amount : null);
                const totalText = total != null ? ('RS.' + Number(total).toFixed(2)) : '—';
                const status = (delivery.status || '').toLowerCase();
                let badgeClass = 'bg-secondary text-white';
                if (status === 'pending') badgeClass = 'bg-warning text-dark';
                else if (status.includes('transit')) badgeClass = 'bg-info text-white';
                else if (status.includes('complete')) badgeClass = 'bg-success text-white';
                else if (status === 'cancelled' || status === 'canceled') badgeClass = 'bg-danger text-white';

                const rowHtml = `
                    <tr data-order-id="${delivery.id}">
                        <td>${($tbody.find('tr').length + 1)}</td>
                        <td>#ORD-${delivery.id}</td>
                        <td>${delivery.customer_name || 'Guest'}</td>
                        <td>${delivery.delivery_address || '—'}</td>
                        <td>${delivery.delivery_time || delivery.order_date || '—'}</td>
                        <td>${totalText}</td>
                        <td>${delivery.payment_method || '—'}</td>
                        <td><span class="badge status-badge ${badgeClass}">${(delivery.status || '—')}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(this, ${delivery.id})"><i class="fas fa-eye"></i></button>
                            ${status === 'pending' ? `<button class=\"btn btn-sm btn-success\" onclick=\"startDelivery(this, '${window.routes.startDeliveryBase ? window.routes.startDeliveryBase.replace(/\/0$/, '/' + delivery.id) : ''}')\"><i class=\"fas fa-play\"></i></button>` : ''}
                            <button class="btn btn-sm btn-primary" onclick="completeDelivery(this, ${delivery.id})"><i class="fas fa-check"></i></button>
                        </td>
                    </tr>`;
                $tbody.prepend(rowHtml);
                markSeen(delivery.id);
            }

            function markSeen(id) {
                $.post(window.routes.markSeen, {
                    id
                });
            }

            function showNotification(message) {
                const el = $('<div class="notify"></div>').text(message);
                $('body').append(el);
                setTimeout(() => el.fadeOut(300, () => el.remove()), 3000);
            }

            function playSound() {
                document.getElementById('notifySound').play();
            }

            function notifyBrowser(delivery) {
                try {
                    playSound();
                } catch (e) {}
                if ('Notification' in window) {
                    if (Notification.permission === 'granted') {
                        new Notification(`New Delivery #${delivery.id}`, {
                            body: delivery.delivery_address || 'New delivery assigned',
                            tag: `delivery-${delivery.id}`
                        });
                    } else if (Notification.permission !== 'denied') {
                        Notification.requestPermission().then(function(permission) {
                            if (permission === 'granted') {
                                new Notification(`New Delivery #${delivery.id}`, {
                                    body: delivery.delivery_address || 'New delivery assigned',
                                    tag: `delivery-${delivery.id}`
                                });
                            }
                        });
                    }
                }
            }

            function updateBadge() {
                $('#deliveryBadge').text(unreadCount).show();
            }

            let timerId = null;

            function start() {
                if (timerId) return;
                poll();
                timerId = setInterval(poll, 5000); // 5 seconds
            }

            return {
                start
            };

        })();
    </script>
    <script>
        $(document).ready(function() {
            Deliverypoller.start();
        });

        function startDelivery(btn, routeUrl) {
            fetch(routeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const row = btn.closest('tr');


                        const badge = row.querySelector('.status-badge');
                        badge.className = 'badge badge-status bg-warning text-dark';
                        badge.textContent = 'In Transit';

                        const actions = row.querySelector('td:last-child');
                        actions.innerHTML = `
    <button class="btn-action btn-outline-action">
        <i class="fas fa-map"></i> View Route
    </button>
    <button class="btn-action btn-success-action"
        onclick="completeDelivery(this, '${routeUrl.replace('start', 'complete')}')">
        <i class="fas fa-check"></i> Mark Complete
    </button>
    `;
                        // local notification
                        const el = document.createElement('div');
                        el.className = 'notify';
                        el.textContent = 'Delivery started successfully!';
                        document.body.appendChild(el);
                        setTimeout(() => {
                            el.remove();
                        }, 3000);
                    } else {
                        const el = document.createElement('div');
                        el.className = 'notify';
                        el.textContent = (data.message || 'Failed to start delivery');
                        document.body.appendChild(el);
                        setTimeout(() => {
                            el.remove();
                        }, 3000);
                    }
                })
                .catch(() => {
                    const el = document.createElement('div');
                    el.className = 'notify';
                    el.textContent = 'Failed to start delivery. Try again.';
                    document.body.appendChild(el);
                    setTimeout(() => {
                        el.remove();
                    }, 3000);
                });
        }
    </script>
@endpush
