<div class="content-container content-section active" id="dashboard">
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
            <div class="filter-tabs">
                <button class="filter-tab active">All</button>
                <button class="filter-tab">Urgent</button>
                <button class="filter-tab">Nearby</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="deliveryOrdersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>ETA / Delivery Time</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>#ORD-{{ $order->id }}</td>
                            <td>{{ optional($order->customer)->name ?? 'Guest' }}</td>
                            <td>{{ $order->orderItems->count() }} item{{ $order->orderItems->count() > 1 ? 's' : '' }}
                            </td>
                            <td>{{ $order->delivery_time ?? ($order->order_date ?? '—') }}</td>
                            <td>{{ isset($order->total_amount) ? '₦' . number_format($order->total_amount, 2) : '—' }}
                            </td>
                            <td>{{ $order->payment_method ?? '—' }}</td>
                            <td><span class="badge badge-status">{{ ucfirst($order->status) }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="viewOrder({{ $order->id }})"><i class="fas fa-eye"></i></button>
                                @if ($order->status === 'pending')
                                    <button class="btn btn-sm btn-success"
                                        onclick="startDelivery(this, {{ $order->id }})"><i
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
