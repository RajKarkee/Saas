@extends('restaurant.layout.app')

@section('content')
    <div class="container mt-20">
        <h3>Manage your Staff</h3>
        {{-- Toast container for flash messages --}}
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Staff Members</h5>
                <a href="{{ route('admin.restaurant.staff.create') }}" class="btn btn-primary float-end">Add New Staff</a>
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
                                    @foreach ($staff as $index => $member)
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
                                            <td>{{ $member->role == 0 ? 'Manager' : ($member->role == 1 ? 'Staff' : 'Delivery Man') }}
                                            </td>
                                            <td>{{ ucfirst($member->status ?? 'inactive') }}</td>
                                            <td>
                                                <a href="{{ route('admin.restaurant.staff.edit', $member->id) }}"
                                                    class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="Edit"><i
                                                        class="fas fa-edit"></i></a>
                                                <form method="post"
                                                    action="{{ route('admin.restaurant.staff.destroy', $member->id) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-delete"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- DataTables JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

        <!-- Optional: Responsive extension -->
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> --}}

        <!-- Toast helper + DataTables init -->
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
            //     t.style.minWidth = '240px';
            //     t.style.marginTop = '8px';
            //     t.style.padding = '12px 14px';
            //     t.style.borderRadius = '8px';
            //     t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.08)';
            //     t.style.color = '#fff';
            //     t.style.fontSize = '14px';
            //     t.style.display = 'flex';
            //     t.style.alignItems = 'center';
            //     t.style.justifyContent = 'space-between';
            //     if (type === 'error') t.style.background = 'linear-gradient(90deg,#ff6b6b,#ff3b3b)';
            //     else t.style.background = 'linear-gradient(90deg,#34d399,#10b981)';

            //     const span = document.createElement('div');
            //     span.textContent = message;
            //     t.appendChild(span);

            //     const close = document.createElement('button');
            //     close.textContent = '×';
            //     close.style.background = 'transparent';
            //     close.style.border = 'none';
            //     close.style.color = 'rgba(255,255,255,0.9)';
            //     close.style.fontSize = '18px';
            //     close.style.cursor = 'pointer';
            //     close.addEventListener('click', () => t.remove());
            //     t.appendChild(close);

            //     container.appendChild(t);
            //     setTimeout(() => t.remove(), 6000);
            // }

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


            });
        </script>
    @endpush
