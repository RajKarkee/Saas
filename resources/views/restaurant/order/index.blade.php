@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Orders</h2>
                <p class="text-muted mb-0">List of recent orders for this restaurant.</p>
            </div>
        </div>

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="orders_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Placed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders ?? [] as $i => $o)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>#{{ $o->id }}</td>
                                    <td>{{ optional($o->customer)->name ?? (optional($o->customer)->email ?? 'Guest') }}
                                    </td>
                                    <td>{{ number_format($o->total_amount ?? 0, 2) }}</td>
                                    <td>{{ ucfirst($o->status ?? 'n/a') }}</td>
                                    <td>{{ $o->payment_method ?? '—' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($o->order_date ?? $o->created_at)->format('Y-m-d H:i') }}
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-secondary"
                                            href="{{ route('admin.restaurant.orders.show', $o->id) }}">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        function createToast(message, type = 'error') {
            const container = document.getElementById('toastContainer') || (function() {
                const c = document.createElement('div');
                c.id = 'toastContainer';
                c.style.position = 'fixed';
                c.style.top = '1rem';
                c.style.right = '1rem';
                c.style.zIndex = 9999;
                document.body.appendChild(c);
                return c;
            })();

            const t = document.createElement('div');
            t.className = 'toast-item ' + type;
            t.style.minWidth = '240px';
            t.style.marginTop = '8px';
            t.style.padding = '12px 14px';
            t.style.borderRadius = '8px';
            t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.08)';
            t.style.color = '#fff';
            t.style.fontSize = '14px';
            t.style.display = 'flex';
            t.style.alignItems = 'center';
            t.style.justifyContent = 'space-between';
            t.style.background = type === 'success' ? 'linear-gradient(90deg,#34d399,#10b981)' :
                'linear-gradient(90deg,#ff6b6b,#ff3b3b)';

            const span = document.createElement('div');
            span.textContent = message;
            t.appendChild(span);
            const close = document.createElement('button');
            close.textContent = '×';
            close.style.background = 'transparent';
            close.style.border = 'none';
            close.style.color = 'rgba(255,255,255,0.9)';
            close.style.fontSize = '18px';
            close.style.cursor = 'pointer';
            close.addEventListener('click', () => t.remove());
            t.appendChild(close);
            container.appendChild(t);
            setTimeout(() => t.remove(), 6000);
        }

        $(document).ready(function() {
            @if (session('success'))
                createToast({!! json_encode(session('success')) !!}, 'success');
            @endif
            @if (session('error'))
                createToast({!! json_encode(session('error')) !!}, 'error');
            @endif
            @if ($errors->any())
                createToast({!! json_encode($errors->first()) !!}, 'error');
            @endif

            $('#orders_table').DataTable({
                responsive: true
            });

            // View buttons now navigate via link
        });
    </script>
@endpush
