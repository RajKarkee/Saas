@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        {{-- Toast container for flash messages --}}
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Menu Items for {{ $category->name ?? 'Unknown' }}</h2>
                <p class="text-muted mb-0">Create and manage menu items.</p>
            </div>
            <div>
                <a href="{{ route('admin.restaurant.menu.items.create', $category->id) }}" class="btn btn-primary">Add
                    Item</a>
            </div>
        </div>

        {{-- Breadcrumbs --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant.menu.categories.index') }}">Menus</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Items</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="items_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Available</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items ?? [] as $i => $it)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if (isset($item_images[$it->id]) && count($item_images[$it->id]) > 0)
                                            <img src="{{ asset('storage/' . $item_images[$it->id][0]->image_url) }}"
                                                alt="{{ $it->name }}" width="60" height="60"
                                                style="object-fit:cover;border-radius:6px;">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $it->name }}</td>
                                    <td>{{ $it->category->name ?? '—' }}</td>
                                    <td>{{ number_format($it->price, 2) }}</td>
                                    <td>{{ $it->is_available ? 'Yes' : 'No' }}</td>
                                    <td>{{ $it->stock_quantity }}</td>
                                    <td>
                                        <a href="{{ route('admin.restaurant.menu.items.edit', $it->id) }}"
                                            class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="tooltip"
                                            data-bs-placement='bottom' title="Edit"><i class="fas fa-edit"></i></a>
                                        <form method="post"
                                            action="{{ route('admin.restaurant.menu.items.destroy', $it->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-delete" type='submit'
                                                data-bs-toggle="tooltip" data-bs-placement='bottom' title='Delete'><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('admin.restaurant.menu.items.addons.index', $it->id) }}"
                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Add addon to this item"><i
                                                class="fas fa-list"></i></a>
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
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('#items_table').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [1, 7] // Disable ordering on Image and Actions columns
                }]
            });
            @if (session('success'))
                createToast({!! json_encode(session('success')) !!}, 'success');
            @endif
            @if (session('error'))
                createToast({!! json_encode(session('error')) !!}, 'error');
            @endif
            @if ($errors->any())
                createToast({!! json_encode($errors->first()) !!}, 'error');
            @endif
        });
    </script>
    {{-- <script>
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



        // Show server flash messages as toasts
        $(document).ready(function() {
            // show server flash messages
        


            const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // $(document).on('click', '.btn-delete', function() {
            //     if (!confirm('Delete this item?')) return;
            //     const action = this.dataset.action;
            //     fetch(action, {
            //             method: 'POST',
            //             headers: {
            //                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
            //                 'X-Requested-With': 'XMLHttpRequest'
            //             },
            //             body: (new FormData()).append('_method', 'DELETE') || new FormData()
            //         })
            //         .then(r => r.json().catch(() => ({}))).then(data => {
            //             if (!data || data.status === 'error') {
            //                 createToast(data.message || 'Failed', 'error');
            //                 return;
            //             }
            //             $(this).closest('tr').remove();
            //             createToast(data.message || 'Deleted', 'success');
            //         }).catch(() => createToast('Failed', 'error'));
            // });
        });
        // const tooltipTriggerList = [].slice.call(document.querySelectorAll(
        //             '[data-bs-toggle="tooltip"]') tooltipTriggerList.map(function(tooltipTriggerEl) {
        //             return new bootstrap.Tooltip(tooltipTriggerEl)
        //         });
    </script> --}}
@endpush
