@extends('admin.layout.app')
@section('title', 'Restaurent_Admins')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Admins</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin index</li>
                </ol>
            </nav>
        </div>

    </div>

    <div class="content-card">
        <div class="content-card-body">
            <div class="table-responsive">
                <table id="admin_restaurant_table">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>No. of Restaurants</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $index => $admin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        // Use the relation names provided by your controller: adminPhoto and adminRestaurant
                                        // avoid calling ->first() on null by using optional()
                                        $photoRecord = $admin->adminPhoto ?? optional($admin->adminPhotos)->first();
                                        $photoPath = $photoRecord->photo_path ?? ($admin->image ?? null);
                                        $imageUrl = $photoPath
                                            ? (\Illuminate\Support\Str::startsWith($photoPath, ['http://', 'https://'])
                                                ? $photoPath
                                                : asset('storage/' . $photoPath))
                                            : asset('images/default-avatar.png'); // ensure public/images/default-avatar.png exists
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $admin->name ?? 'Admin' }}"
                                        class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                                </td>
                                <td>{{ $admin->name ?? '—' }}</td>
                                <td>{{ $admin->email ?? '—' }}</td>
                                <td>{{ $admin->adminRestaurant->restaurant_count ?? '0' }}</td>
                                <td>
                                    @php $status = strtolower($admin->status ?? 'inactive'); @endphp
                                    @if ($status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif ($status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('super_admin.admins.edit', $admin->id) }}"
                                        class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('super_admin.admins.destroy', $admin->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                            onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#admin_restaurant_table').DataTable({
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
