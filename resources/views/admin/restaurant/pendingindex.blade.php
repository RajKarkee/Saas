@extends('admin.layout.app')
@section('title', 'Pending Restaurants')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Pending Restaurants</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pending Restaurants</li>
                </ol>
            </nav>
        </div>

    </div>

    <div class="content-card">
        <div class="content-card-body">
            <div class="table-responsive">
                <table id="pending_restaurant_table">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Name</th>
                            <th>Owner Name</th>
                            <th>Owner Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($restaurants as $index => $restaurant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ $restaurant->owner->name ?? '—' }}</td>
                                <td>{{ $restaurant->owner->email ?? '—' }}</td>
                                <td>
                                    @php $status = strtolower($restaurant->status ?? 'inactive'); @endphp
                                    @if ($status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif ($status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('super_admin.restaurant.edit', $restaurant->id) }}"
                                        class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pending_restaurant_table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
            });
        });
    </script>

    <script>
        // Toast helper (copied from other admin views)
        function createToast(message, type = 'error') {
            const container = document.getElementById('toastContainer') || (() => {
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
            if (type === 'error') t.style.background = 'linear-gradient(90deg,#ff6b6b,#ff3b3b)';
            else t.style.background = 'linear-gradient(90deg,#34d399,#10b981)';

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

        // Show session success as toast on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                createToast(@json(session('success')), 'success');
            @endif
        });
    </script>

    <style>
        /* small toast item styling for accessibility */
        #toastContainer .toast-item {
            opacity: 0.98;
        }
    </style>
@endsection
