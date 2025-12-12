@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="mb-1 fw-semibold">Order #{{ $order->id }}</h2>
                <p class="text-muted mb-0">Placed
                    {{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->diffForHumans() }}</p>
            </div>
            <div>
                <a href="{{ route('admin.restaurant.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-7">
                <div class="card shadow-sm">
                    <div class="card-header border-0 pb-0">
                        <h6 class="fw-semibold mb-0">Items</h6>
                    </div>
                    <div class="card-body">
                        @if (($items ?? collect())->isEmpty())
                            <p class="text-muted mb-0">No items found for this order.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $it)
                                            <tr>
                                                <td>{{ $it->menuItem->name ?? 'Item' }}</td>
                                                <td class="text-end">{{ $it->quantity ?? 1 }}</td>
                                                <td class="text-end">{{ number_format($it->unit_price ?? 0, 2) }}</td>
                                                <td class="text-end">
                                                    {{ number_format($it->total_price ?? ($it->unit_price ?? 0) * ($it->quantity ?? 1), 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header border-0 pb-0">
                        <h6 class="fw-semibold mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status</span>
                            <span class="fw-semibold">{{ ucfirst($order->status ?? 'n/a') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment</span>
                            <span class="fw-semibold">{{ $order->payment_method ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span
                                class="fw-semibold">{{ number_format($order->subtotal ?? ($order->total_amount ?? 0), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax</span>
                            <span class="fw-semibold">{{ number_format($order->tax ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total</span>
                            <span class="fw-bold">{{ number_format($order->total_amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header border-0 pb-0">
                        <h6 class="fw-semibold mb-0">Customer</h6>
                    </div>
                    <div class="card-body">
                        @if ($customer)
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar rounded-circle bg-primary text-white fw-semibold"
                                    style="width:48px;height:48px;display:inline-flex;align-items:center;justify-content:center;">
                                    {{ \Illuminate\Support\Str::of($customer->name ?? ($customer->email ?? 'GU'))->substr(0, 2)->upper() }}
                                </span>
                                <div>
                                    <div class="fw-semibold">{{ $customer->name ?? 'Guest User' }}</div>
                                    <div class="small text-muted">{{ $customer->email ?? '—' }}</div>
                                    <div class="small text-muted">{{ $customer->phone ?? '' }}</div>
                                </div>
                            </div>
                            @if (!empty($order->delivery_address))
                                <div class="mt-3">
                                    <div class="text-muted small">Delivery Address</div>
                                    <div class="fw-semibold">{{ $order->delivery_address }}</div>
                                </div>
                            @endif
                        @else
                            <p class="text-muted mb-0">No customer data linked to this order.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // function createToast(message, type = 'success') {
        //     const container = document.getElementById('toastContainer') || (function() {
        //         const c = document.createElement('div');
        //         c.id = 'toastContainer';
        //         c.style.position = 'fixed';
        //         c.style.top = '1rem';
        //         c.style.right = '1rem';
        //         c.style.zIndex = 9999;
        //         document.body.appendChild(c);
        //         return c;
        //     })();
        //     const t = document.createElement('div');
        //     t.className = 'toast-item ' + type;
        //     t.textContent = message;
        //     t.style.padding = '10px 14px';
        //     t.style.marginTop = '8px';
        //     t.style.borderRadius = '6px';
        //     t.style.color = '#fff';
        //     t.style.background = type === 'success' ? 'linear-gradient(90deg,#34d399,#10b981)' :
        //         'linear-gradient(90deg,#ff6b6b,#ff3b3b)';
        //     container.appendChild(t);
        //     setTimeout(() => t.remove(), 5000);
        // }
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                createToast(@json(session('success')), 'success');
            @endif
            @if (session('error'))
                createToast(@json(session('error')), 'error');
            @endif
            @if ($errors->any())
                createToast(@json($errors->first()), 'error');
            @endif
        });
    </script>
@endpush
