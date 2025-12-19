@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        {{-- Toast container for flash messages --}}
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Menu Categories</h2>
                <p class="text-muted mb-0">Manage menu categories for your restaurants.</p>
            </div>
            <div>
                <a href="{{ route('admin.restaurant.menu.categories.create') }}" class="btn btn-primary">Add Category</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categories_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items ?? [] as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                                width="50" height="50">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category_name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($item->description, 50) }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        @if ($item->is_available)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.restaurant.menu.items.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.restaurant.menu.items.destroy', $item->id) }}"
                                            method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- jQuery & DataTables --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> --}}

    {{-- Bootstrap JS for tooltips (ensure Bootstrap is loaded) --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script>
        // function createToast(message, type = 'error') {
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

        $(document).ready(function() {
            // Initialize DataTable
            $('#categories_table').DataTable({
                responsive: true
            });

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Show server flash messages as toasts
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
@endpush
