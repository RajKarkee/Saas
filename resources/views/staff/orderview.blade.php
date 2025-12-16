@extends('staff.layout.app')
@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-receipt me-2"></i>Order Details #{{ $order->id }}</h2>
                <p class="text-muted mb-0">View complete order information and manage delivery</p>
            </div>
            <div>
                <a href="{{ route('restaurant.staff.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Order Summary Card -->
            <div class="col-lg-8">
                <!-- Order Status & Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'processing' => 'primary',
                                    'preparing' => 'primary',
                                    'ready' => 'success',
                                    'out_for_delivery' => 'info',
                                    'delivered' => 'success',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $statusColor = $statusColors[$order->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusColor }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Order ID</small>
                                <div class="fw-semibold">#{{ $order->id }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Order Date</small>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Order Type</small>
                                <div class="fw-semibold">{{ ucfirst($order->order_type ?? 'Delivery') }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Payment Method</small>
                                <div class="fw-semibold">
                                    <span class="badge bg-{{ $order->payment_method === 'cash' ? 'success' : 'primary' }}">
                                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>
                            @if ($order->delivery_time)
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Estimated Delivery Time</small>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($order->delivery_time)->format('M d, Y h:i A') }}</div>
                                </div>
                            @endif
                            @if ($order->notes)
                                <div class="col-12 mb-3">
                                    <small class="text-muted">Order Notes</small>
                                    <div class="alert alert-info mb-0 mt-1">
                                        <i class="fas fa-sticky-note me-2"></i>{{ $order->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Items ({{ $items->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">Image</th>
                                        <th width="25%">Item</th>
                                        <th width="15%">Category</th>
                                        <th width="10%">Qty</th>
                                        <th width="12%">Price</th>
                                        <th width="13%">Total</th>
                                        <th width="15%">Addons</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>
                                                @if ($item->item_image)
                                                    <img src="{{ Storage::url($item->item_image) }}"
                                                        alt="{{ $item->item_image_alt ?? $item->item_name }}"
                                                        class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="fas fa-utensils text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $item->item_name }}</div>
                                                @if ($item->item_description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($item->item_description, 40) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->item_catagory)
                                                    <span class="badge bg-secondary">{{ $item->item_catagory }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-primary">{{ $item->quantity }}</span></td>
                                            <td>₦{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="fw-semibold">₦{{ number_format($item->total_price, 2) }}</td>
                                            <td>
                                                @if ($item->addon_name)
                                                    <small>
                                                        <i class="fas fa-plus-circle text-success me-1"></i>
                                                        {{ $item->addon_name }}
                                                        @if ($item->addon_price)
                                                            <br><span
                                                                class="text-muted">+₦{{ number_format($item->addon_price, 2) }}</span>
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No items found in this order</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end fw-semibold">Total Amount:</td>
                                        <td colspan="2" class="fw-bold fs-5 text-primary">
                                            ₦{{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Customer & Delivery Info -->
            <div class="col-lg-4">
                <!-- Customer Information -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h6>
                    </div>
                    <div class="card-body">
                        @if ($order->customer_name)
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px; font-size: 20px;">
                                    {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    @if ($order->customer_email)
                                        <small class="text-muted">{{ $order->customer_email }}</small>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Guest Order (No customer info)
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Person -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-motorcycle me-2"></i>Delivery Person</h6>
                    </div>
                    <div class="card-body">
                        @if ($order->delivery_person_id)
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px; font-size: 20px;">
                                    {{ strtoupper(substr($order->delivery_person_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->delivery_person_name }}</div>
                                    @if ($order->delivery_person_phone)
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $order->delivery_person_phone }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary w-100"
                                onclick="contactDelivery('{{ $order->delivery_person_phone ?? '' }}')">
                                <i class="fas fa-phone me-2"></i>Contact Delivery Person
                            </button>
                        @else
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>No Delivery Person Assigned</strong>
                            </div>
                            <button class="btn btn-primary w-100"
                                onclick="window.location.href='{{ route('restaurant.staff.index') }}'">
                                <i class="fas fa-user-plus me-2"></i>Assign Delivery Person
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($order->status === 'pending')
                                <button class="btn btn-success" onclick="updateStatus({{ $order->id }}, 'accepted')">
                                    <i class="fas fa-check-circle me-2"></i>Confirm Order Start Cooking
                                </button>
                            @endif

                            {{-- @if (in_array($order->status, ['confirmed', 'pending']))
                                <button class="btn btn-primary" onclick="updateStatus({{ $order->id }}, 'preparing')">
                                    <i class="fas fa-utensils me-2"></i>Start Preparing
                                </button>
                            @endif

                            @if ($order->status === 'preparing')
                                <button class="btn btn-info" onclick="updateStatus({{ $order->id }}, 'ready')">
                                    <i class="fas fa-check me-2"></i>Mark as Ready
                                </button>
                            @endif

                            @if ($order->status === 'ready' && $order->delivery_person_id)
                                <button class="btn btn-warning"
                                    onclick="updateStatus({{ $order->id }}, 'out_for_delivery')">
                                    <i class="fas fa-shipping-fast me-2"></i>Out for Delivery
                                </button>
                            @endif --}}

                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Print Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge {
            padding: 0.4em 0.8em;
            font-weight: 500;
        }

        @media print {

            .btn,
            .card-header,
            nav {
                display: none !important;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateStatus(orderId, status) {
            if (!confirm('Are you sure you want to update this order status to ' + status.replace('_', ' ') + '?')) {
                return;
            }

            $.ajax({
                url: '{{ route('restaurant.staff.update-order-status') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        showToast('Order status updated successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        alert(response.message || 'Failed to update status');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred while updating order status');
                }
            });
        }

        function contactDelivery(phone) {
            if (!phone) {
                alert('No phone number available for this delivery person');
                return;
            }
            alert('Contact Delivery Person:\nPhone: ' + phone);
        }

        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? '#10b981' : '#ef4444';
            const toast = $('<div>')
                .css({
                    position: 'fixed',
                    top: '20px',
                    right: '20px',
                    background: bgColor,
                    color: 'white',
                    padding: '15px 25px',
                    borderRadius: '8px',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                    zIndex: 9999,
                    fontWeight: '500'
                })
                .text(message)
                .appendTo('body')
                .hide()
                .fadeIn(300);

            setTimeout(() => {
                toast.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>
@endsection
