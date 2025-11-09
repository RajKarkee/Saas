@extends('delivery.layout.app')
@section('content')
    <div class="content-container content-section active" id="deliveries-full">
        <div class="page-header">
            <h1 class="page-title">Ongoing Deliveries</h1>
            <p class="page-subtitle">Deliveries currently in transit assigned to you</p>
        </div>

        <div class="card-container">
            <div class="card-header-section">
                <h2 class="card-title">In Transit</h2>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="ongoingDeliveriesTable">
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
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr>
                                <td data-label="#">{{ $index + 1 }}</td>
                                <td data-label="Order ID">#ORD-{{ $order->id }}</td>
                                <td data-label="Customer">{{ $order->customer_name ?? 'Guest' }}</td>
                                <td data-label="ETA">{{ $order->delivery_time ?? ($order->order_date ?? '—') }}</td>
                                <td data-label="Total">
                                    @php
                                        $displayTotal = $order->order_total_price ?? ($order->total_amount ?? null);
                                    @endphp
                                    {{ $displayTotal !== null ? 'RS.' . number_format($displayTotal, 2) : '—' }}
                                </td>
                                <td data-label="Payment">{{ $order->payment_method ?? '—' }}</td>
                                @php
                                    $s = strtolower($order->delivery_status ?? ($order->status ?? ''));
                                    $badgeClass = 'bg-secondary text-white';
                                    if ($s === 'pending') {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif (strpos($s, 'transit') !== false) {
                                        $badgeClass = 'bg-info text-white';
                                    } elseif (strpos($s, 'complete') !== false || $s === 'completed') {
                                        $badgeClass = 'bg-success text-white';
                                    } elseif ($s === 'cancelled' || $s === 'canceled') {
                                        $badgeClass = 'bg-danger text-white';
                                    }
                                @endphp
                                <td data-label="Status"><span
                                        class="badge status-badge {{ $badgeClass }}">{{ ucfirst($order->delivery_status ?? $order->status) }}</span>
                                </td>
                                <td data-label="Actions">
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="viewOrder({{ $order->id }})"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-success"
                                        onclick="completeDelivery(this, {{ $order->id }})"><i
                                            class="fas fa-check"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No ongoing deliveries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
