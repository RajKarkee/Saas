@extends('staff.layout.app')
@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">{{ $restaurant->name ?? 'Restaurant' }} - Staff Dashboard</h2>
                <p class="text-muted mb-0">Manage orders and assign delivery personnel</p>
            </div>
            <div>
                <span class="badge bg-primary">{{ $staff->name }}</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Orders</h6>
                                <h3 class="mb-0">{{ $orders->count() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shopping-cart text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pending</h6>
                                <h3 class="mb-0">{{ $orders->where('status', 'pending')->count() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">In Progress</h6>
                                <h3 class="mb-0">{{ $orders->whereIn('status', ['confirmed', 'preparing'])->count() }}
                                </h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-spinner text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="cursor: pointer;" onclick="showDeliveryStaff()">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Delivery Staff</h6>
                                <h3 class="mb-0">{{ $delivery->count() }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-motorcycle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Management Table -->
        <div class="card border-0 shadow-sm" id="ordersSection">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Orders Management</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active"
                            onclick="filterOrders('all')">All</button>
                        <button type="button" class="btn btn-outline-warning"
                            onclick="filterOrders('pending')">Pending</button>
                        <button type="button" class="btn btn-outline-info"
                            onclick="filterOrders('preparing')">Preparing</button>
                        <button type="button" class="btn btn-outline-success"
                            onclick="filterOrders('completed')">Completed</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="ordersTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Order ID</th>
                                <th width="15%">Customer</th>
                                <th width="10%">Total</th>
                                <th width="10%">Payment</th>
                                <th width="10%">Status</th>
                                <th width="15%">Delivery Person</th>
                                <th width="15%">Date</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $order)
                                <tr data-status="{{ $order->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $order->customer_name ?? 'Guest' }}</div>
                                            <small class="text-muted">{{ $order->customer_email ?? '—' }}</small>
                                        </div>
                                    </td>
                                    <td><strong>₦{{ number_format($order->total_amount ?? 0, 2) }}</strong></td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $order->payment_method === 'cash' ? 'success' : 'primary' }}">
                                            {{ ucfirst($order->payment_method ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'preparing' => 'primary',
                                                'ready' => 'success',
                                                'out_for_delivery' => 'info',
                                                'delivered' => 'success',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusColor = $statusColors[$order->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($order->delivery_person_id)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-success me-2"></i>
                                                <span>{{ $order->delivery_person_name }}</span>
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="showAssignModal({{ $order->id }}, '{{ $order->customer_name ?? 'Guest' }}')">
                                                <i class="fas fa-user-plus me-1"></i>Assign
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</small><br>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-info" onclick="viewOrder({{ $order->id }})"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if ($order->status === 'pending')
                                                <button class="btn btn-outline-success"
                                                    onclick="updateOrderStatus({{ $order->id }}, 'confirmed')"
                                                    title="Confirm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No orders found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Delivery Staff Table -->
        <div class="card border-0 shadow-sm" id="deliveryStaffSection" style="display: none;">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-motorcycle me-2"></i>Delivery Staff</h5>
                    <button class="btn btn-sm btn-outline-secondary" onclick="showOrders()">
                        <i class="fas fa-arrow-left me-1"></i>Back to Orders
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Name</th>
                                <th width="15%">Email</th>
                                <th width="12%">Phone</th>
                                <th width="10%">Status</th>
                                <th width="12%">Active Orders</th>
                                <th width="13%">Completed</th>
                                <th width="18%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($delivery as $index => $person)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (isset($person->photo_url) && $person->photo_url)
                                                <img src="{{ asset($person->photo_url) }}" alt="{{ $person->name }}"
                                                    class="rounded-circle me-2"
                                                    style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2"
                                                    style="width: 32px; height: 32px; font-size: 14px;">
                                                    {{ strtoupper(substr($person->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $person->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $person->email ?? '—' }}</td>
                                    <td>{{ $person->phone ?? '—' }}</td>
                                    <td>
                                        @php
                                            // Check if delivery person is currently active (has orders in transit)
                                            $activeOrdersCount = $orders
                                                ->where('delivery_person_id', $person->id)
                                                ->whereIn('status', [
                                                    'confirmed',
                                                    'preparing',
                                                    'ready',
                                                    'out_for_delivery',
                                                ])
                                                ->count();
                                        @endphp
                                        @if ($activeOrdersCount > 0)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-circle-notch fa-spin me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Available
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $activeCount = $orders
                                                ->where('delivery_person_id', $person->id)
                                                ->whereIn('status', [
                                                    'confirmed',
                                                    'preparing',
                                                    'ready',
                                                    'out_for_delivery',
                                                ])
                                                ->count();
                                        @endphp
                                        <span class="badge bg-primary">{{ $activeCount }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $completedCount = $orders
                                                ->where('delivery_person_id', $person->id)
                                                ->whereIn('status', ['delivered', 'completed'])
                                                ->count();
                                        @endphp
                                        <span class="badge bg-success">{{ $completedCount }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-info"
                                                onclick="viewDeliveryPersonOrders({{ $person->id }}, '{{ $person->name }}')"
                                                title="View Orders">
                                                <i class="fas fa-list me-1"></i>Orders
                                            </button>
                                            <button class="btn btn-outline-primary"
                                                onclick="contactDeliveryPerson('{{ $person->phone ?? '' }}', '{{ $person->email ?? '' }}')"
                                                title="Contact">
                                                <i class="fas fa-phone"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-motorcycle fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No delivery staff found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Delivery Person Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Assign Delivery Person
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Order Details</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Order ID:</small>
                                        <div class="fw-semibold" id="modalOrderId">#—</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Customer:</small>
                                        <div class="fw-semibold" id="modalCustomerName">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="deliveryPersonSelect" class="form-label fw-semibold">
                            Select Delivery Person <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="deliveryPersonSelect" required>
                            <option value="">-- Choose Delivery Person --</option>
                            @foreach ($delivery as $person)
                                <option value="{{ $person->id }}">
                                    {{ $person->name }}
                                    @if ($person->phone)
                                        ({{ $person->phone }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($delivery->count() === 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No delivery personnel available. Please add delivery staff first.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="assignDeliveryPerson()"
                        @if ($delivery->count() === 0) disabled @endif>
                        <i class="fas fa-check me-2"></i>Assign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card[onclick] {
            cursor: pointer;
        }

        .card[onclick]:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentOrderId = null;

        function showAssignModal(orderId, customerName) {
            currentOrderId = orderId;
            $('#modalOrderId').text('#' + orderId);
            $('#modalCustomerName').text(customerName);
            $('#deliveryPersonSelect').val('');

            const modal = new bootstrap.Modal(document.getElementById('assignModal'));
            modal.show();
        }

        function assignDeliveryPerson() {
            const deliveryPersonId = $('#deliveryPersonSelect').val();

            if (!deliveryPersonId) {
                alert('Please select a delivery person');
                return;
            }

            if (!currentOrderId) {
                alert('Order ID not found');
                return;
            }


            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Assigning...';


            $.ajax({
                url: '{{ route('restaurant.staff.assign-delivery') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: currentOrderId,
                    delivery_person_id: deliveryPersonId
                },
                success: function(response) {
                    if (response.success) {

                        bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();


                        showToast('Delivery person assigned successfully!', 'success');


                        setTimeout(() => location.reload(), 1000);
                    } else {
                        alert(response.message || 'Failed to assign delivery person');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                },
                error: function(xhr) {
                    let message = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        }

        function updateOrderStatus(orderId, status) {
            if (!confirm('Are you sure you want to update this order status?')) {
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

        function viewOrder(orderId) {

            window.location.href = '/restaurant/orders/' + orderId;
        }

        function filterOrders(status) {
            const rows = document.querySelectorAll('#ordersTable tbody tr[data-status]');

            // Update active button
            document.querySelectorAll('.btn-group button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter rows
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.dataset.status === status ? '' : 'none';
                }
            });
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


        $(document).ready(function() {
            $('[title]').tooltip();
        });


        function showDeliveryStaff() {
            $('#ordersSection').hide();
            $('#deliveryStaffSection').fadeIn(300);
        }

        function showOrders() {
            $('#deliveryStaffSection').hide();
            $('#ordersSection').fadeIn(300);
        }


        function viewDeliveryPersonOrders(personId, personName) {
            showOrders();
            showToast('Filtering orders for ' + personName, 'success');

            // Filter orders table to show only this delivery person's orders
            const rows = document.querySelectorAll('#ordersTable tbody tr[data-status]');
            let visibleCount = 0;

            rows.forEach(row => {
                const deliveryPersonCell = row.cells[6]; // 7th column (Delivery Person)
                const hasThisPerson = deliveryPersonCell && deliveryPersonCell.textContent.includes(personName);

                if (hasThisPerson) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                showToast('No orders found for ' + personName, 'warning');
            }

            // Reset filter buttons
            document.querySelectorAll('.btn-group button').forEach(btn => {
                btn.classList.remove('active');
            });
        }


        function contactDeliveryPerson(phone, email) {
            if (!phone && !email) {
                alert('No contact information available for this delivery person');
                return;
            }

            let message = 'Contact Information:\n\n';
            if (phone) {
                message += 'Phone: ' + phone + '\n';
            }
            if (email) {
                message += 'Email: ' + email;
            }

            alert(message);
        }
    </script>
@endsection
