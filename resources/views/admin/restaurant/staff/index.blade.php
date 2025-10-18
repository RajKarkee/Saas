@extends('admin.layout.app')
@section('title', 'Restaurant Dashboard')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="h4 mb-0">{{ $restaurant->name }}</h3>
                <div class="small text-muted">
                    {{ $restaurant->domain ?? ($restaurant->subdomain ? $restaurant->subdomain . '.' . request()->getHost() : '') }}
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add
                    Staff</button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="mb-3">Overview</h6>
                    <div class="d-flex align-items-center gap-3">
                        @php
                            $logo = $restaurant->logo ?? null;
                            $logoUrl = $logo
                                ? (Str::startsWith($logo, ['http://', 'https://'])
                                    ? $logo
                                    : asset('storage/' . $logo))
                                : asset('images/default-avatar.png');
                        @endphp
                        <img src="{{ $logoUrl }}" alt="logo"
                            style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
                        <div>
                            <div><strong>Owner:</strong> {{ $restaurant->owner->name ?? '—' }}</div>
                            <div class="small text-muted">{{ $restaurant->owner->email ?? '—' }}</div>
                            <div class="mt-2"><strong>Status:</strong>
                                <span
                                    class="badge bg-{{ strtolower($restaurant->status) === 'active' ? 'success' : (strtolower($restaurant->status) === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($restaurant->status ?? 'inactive') }}</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="fw-bold">{{ $restaurant->staff->count() ?? 0 }}</div>
                            <div class="small text-muted">Staff</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold">
                                {{ $restaurant->created_at ? $restaurant->created_at->format('M d, Y') : '—' }}</div>
                            <div class="small text-muted">Created</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-3">
                    <h6 class="mb-3">Recent Staff</h6>
                    @if ($restaurant->staff && $restaurant->staff->count())
                        <div class="list-group">
                            @foreach ($restaurant->staff->take(10) as $s)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $s->name }}</div>
                                        <div class="small text-muted">{{ $s->email }} · {{ $s->role ?? 'Staff' }}
                                        </div>
                                    </div>
                                    <div class="small text-muted">{{ $s->status ?? '—' }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">No staff found for this restaurant.</div>
                    @endif
                </div>

                <div class="card p-3 mt-3">
                    <h6 class="mb-3">Quick Actions</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary me-2">Visit Restaurant Panel</a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#addStaffModal">Add Staff</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('super_admin.restaurant.staff.store') }}" method="POST" id="addStaffForm">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffModalLabel">Add Staff to {{ $restaurant->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="Staff_Member">Staff Member</option>
                                    <option value="Delivery_person">Delivery Person</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
