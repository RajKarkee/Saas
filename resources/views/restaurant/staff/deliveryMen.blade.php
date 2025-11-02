@extends('restaurant.layout.app')

@section('content')
    <div class="container mt-20">
        <h3>Manage your Delivery Men</h3>
        {{-- Toast container for flash messages --}}
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Delivery Men</h5>
                {{-- <a href="{{ route('admin.restaurant.staff.create') }}" class="btn btn-primary float-end">Add New Staff</a> --}}
            </div>
            <div class="card-body">
                <div class="content-card">
                    <div class="content-card-body">
                        <div class="table-responsive">

                            <table id="staff_table">
                                <thead>
                                    <th>S.N.</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveryMen as $index => $member)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if (isset($staff_photos[$member->id]))
                                                    <img src="{{ asset('storage/' . $staff_photos[$member->id]->photo_url) }}"
                                                        alt="{{ $member->name }}" width="50" height="50"
                                                        style="object-fit:cover;border-radius:50%;">
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>{{ ucfirst($member->role) }}</td>
                                            <td>{{ ucfirst($member->status ?? 'inactive') }}</td>
                                            <td>
                                                <a href="{{ route('admin.restaurant.staff.edit', $member->id) }}"
                                                    class="btn btn-sm btn-outline-secondary me-1"><i
                                                        class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    data-action="{{ route('admin.restaurant.staff.destroy', $member->id) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- jQuery first -->
    @endsection
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- DataTables JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

        <!-- Optional: Responsive extension -->
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

        <!-- Toast helper + DataTables init -->
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

                // initialize DataTable
                var staffTable = $('#staff_table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                });


                $(document).on('click', '.btn-delete', function(e) {
                    const btn = this;
                    const action = btn.getAttribute('data-action');
                    if (!action) return;
                    if (!confirm('Are you sure you want to delete this staff member?')) return;

                    const formData = new FormData();
                    formData.append('_method', 'DELETE');

                    fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData,
                    }).then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            createToast(data.message || 'Failed to delete', 'error');
                            return;
                        }
                        // remove row from DataTable
                        staffTable.row($(btn).closest('tr')).remove().draw();
                        createToast(data.message || 'Deleted', 'success');
                    }).catch(err => {
                        console.error(err);
                        createToast('Failed to delete. Try again.', 'error');
                    });
                });
            });
        </script>
    @endpush
