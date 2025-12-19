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
            <p class="page-subtitle">Welcome back {{ auth()->user()->name }}! Here's what's happening with your deliveries
                today.</p>
        </div>





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


                                    @if ($order->delivery_status === 'pending')
                                        {
                                        @php $badgeClass = 'bg-warning text-dark'; @endphp
                                        }
                                    @elseif($order->delivery_status === 'in_transit')
                                        {
                                        @php $badgeClass = 'bg-info text-white'; @endphp
                                        }
                                    @elseif($order->delivery_status === 'completed')
                                        {
                                        @php $badgeClass = 'bg-success text-white' @endphp
                                        }
                                    @endif



                                    <td data-label="Status"><span
                                            class="badge status-badge {{ $badgeClass }}">{{ ucfirst($order->delivery_status) }}</span>
                                    </td>
                                    <td data-label="Actions">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="viewOrder(this, {{ $order->id }})"><i
                                                class="fas fa-eye"></i></button>
                                        @if ($order->status === 'pending')
                                            <button class="btn btn-sm btn-success" data-id="{{ $order->id }}"
                                                onclick="startDelivery(this)"><i class="fas fa-play"></i></button>
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
        @endsection
        @push('scripts')
            <script>
                // Echo listener: insert assigned orders without refresh
                (function() {
                    const tbody = document.getElementById('deliveryTableBody');
                    const badge = document.getElementById('deliveryBadge');
                    const sound = document.getElementById('notifySound');
                    // Request notification permission once
                    if ('Notification' in window && Notification.permission === 'default') {
                        Notification.requestPermission().catch(() => {});
                    }

                    function reindexRows() {
                        const rows = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];
                        rows.forEach((tr, i) => {
                            const first = tr.querySelector('td');
                            if (first) first.textContent = String(i + 1);
                        });
                    }

                    function computeBadgeClass(status) {
                        const s = String(status || '').toLowerCase();
                        if (s === 'pending') return 'bg-warning text-dark';
                        if (s.includes('transit')) return 'bg-info text-white';
                        if (s.includes('complete')) return 'bg-success text-white';
                        if (s === 'cancelled' || s === 'canceled') return 'bg-danger text-white';
                        return 'bg-secondary text-white';
                    }

                    function fmtTotal(o) {
                        const total = (o && (o.order_total_price ?? o.total_amount));
                        return (total != null) ? ('RS.' + Number(total).toFixed(2)) : '—';
                    }

                    function prependOrderRow(o) {
                        if (!tbody || !o) return;
                        const id = o.id || o.order_id;
                        if (!id) return;
                        if (tbody.querySelector(`tr[data-order-id="${id}"]`)) return;

                        const badgeClass = computeBadgeClass(o.status || 'assigned');
                        const row = document.createElement('tr');
                        row.setAttribute('data-order-id', id);
                        row.innerHTML = `
                    <td></td>
                    <td>#ORD-${id}</td>
                    <td>${o.customer_name || 'Guest'}</td>
                    <td>${o.delivery_time || o.order_date || '—'}</td>
                    <td>${fmtTotal(o)}</td>
                    <td>${o.payment_method || '—'}</td>
                    <td><span class="badge status-badge ${badgeClass}">${(o.status || 'Assigned')}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(this, ${id})"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-primary" onclick="completeDelivery(this, ${id})"><i class="fas fa-check"></i></button>
                    </td>`;
                        tbody.insertBefore(row, tbody.firstChild);
                        reindexRows();

                        if (badge) {
                            const c = Number(badge.textContent || '0') + 1;
                            badge.textContent = String(c);
                            badge.style.display = 'inline-block';
                        }
                        try {
                            if (sound) sound.play();
                        } catch (_) {}
                        if (typeof showNotification === 'function') {
                            showNotification(`New delivery assigned: #ORD-${id}`, 'success');
                        }
                        if ('Notification' in window) {
                            if (Notification.permission === 'granted') {
                                new Notification('New Delivery Assigned', {
                                    body: `Order #${id} assigned to you.`
                                });
                            } else if (Notification.permission !== 'denied') {
                                Notification.requestPermission();
                            }
                        }
                    }

                    window.addEventListener('delivery:assigned', (ev) => {
                        console.log('[Dashboard] delivery:assigned payload', ev.detail);
                        prependOrderRow(ev.detail);
                    });
                })();

                $ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                function startDelivery(button) {
                    let orderId = $(button).data('id');
                    $.ajax({
                        url: `/restaurant/delivery/start/${orderId}`,
                        type: "POST",
                        data: {
                            order_id: orderId
                        },
                        success: function(response) {
                            $(button).prop('disabled', true).html('<i class="fas fa-check"></i> In Transit');
                            alert('Delivery started successfully', response);
                        },
                        error: function(xhr) {
                            alert('Error starting delivery: ' + xhr.responseJSON.message);
                        }
                    });
                }
            </script>
        @endpush
