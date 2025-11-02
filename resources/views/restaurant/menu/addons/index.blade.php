@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Menu Addons for {{ $item->name }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.restaurant.menu.items.addons.create', $item->id) }}" class="btn btn-primary">Add
                    Addon</a>
            </div>
        </div>
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant.menu.categories.index') }}">Menus</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.restaurant.menu.items.index', $category->id) }}">Items</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Addons</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="addons_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Name</th>
                                <th>Additional Price</th>
                                <th>Available</th>
                                <th>Max Select</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($addons ?? [] as $i => $a)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $a->menuItem->name ?? '—' }}</td>
                                    <td>{{ $a->name }}</td>
                                    <td>{{ number_format($a->additional_price, 2) }}</td>
                                    <td>{{ $a->is_available ? 'Yes' : 'No' }}</td>
                                    <td>{{ $a->max_select }}</td>
                                    <td>
                                        <a href="{{ route('admin.restaurant.menu.items.addons.edit', $a->id) }}"
                                            class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-outline-danger btn-delete"
                                            data-action="{{ route('admin.restaurant.menu.items.addons.destroy', $a->id) }}"><i
                                                class="fas fa-trash"></i></button>
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
        $(document).ready(function() {
            $('#addons_table').DataTable({
                responsive: true
            });
            $(document).on('click', '.btn-delete', function() {
                if (!confirm('Delete this addon?')) return;
                const action = this.dataset.action;
                fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: (new FormData()).append('_method', 'DELETE') || new FormData()
                    })
                    .then(r => r.json().catch(() => ({}))).then(data => {
                        if (!data || data.status === 'error') {
                            alert(data.message || 'Failed');
                            return;
                        }
                        $(this).closest('tr').remove();
                    }).catch(() => alert('Failed'));
            });
        });
    </script>
@endpush
