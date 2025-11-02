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
                                <th>Name</th>
                                <th>Description</th>
                                <th>Position</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories ?? [] as $i => $cat)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $cat->name }}</td>
                                    <td>{{ Str::limit($cat->description, 80) }}</td>
                                    <td>{{ $cat->position }}</td>
                                    <td>{{ $cat->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('admin.restaurant.menu.categories.edit', $cat->id) }}"
                                            class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-edit"></i></a>
                                        <form method="post"
                                            action="{{ route('admin.restaurant.menu.categories.destroy', $cat->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-delete"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('admin.restaurant.menu.items.index', $cat->id) }}"
                                            class="btn btn-sm btn-outline-info"><i class="fas fa-list"></i></a>
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
            t.textContent = message;
            t.style.padding = '10px 14px';
            t.style.marginTop = '8px';
            t.style.borderRadius = '6px';
            t.style.color = '#fff';
            t.style.background = type === 'success' ? 'linear-gradient(90deg,#34d399,#10b981)' :
                'linear-gradient(90deg,#ff6b6b,#ff3b3b)';
            container.appendChild(t);
            setTimeout(() => t.remove(), 5000);
        }

        $(document).ready(function() {
            $('#categories_table').DataTable({
                responsive: true
            });

            // show server flash messages as toasts
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
