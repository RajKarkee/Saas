@extends('restaurant.layout.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="container-fluid py-4">

        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>

        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Admin Dashboard</h2>
                <p class="text-muted mb-0">Manage the single restaurant linked to your account – review domains, status and
                    staff.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Restaurant Overview</h5>
                            <p class="text-muted small mb-0">Keep your branding and domain details up to date.</p>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($restaurants->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-store fa-3x text-muted"></i></div>
                                <h6 class="fw-semibold">No restaurant yet</h6>
                                <p class="text-muted small mb-0">Once your restaurant is created it will appear here.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Domain</th>
                                            <th>Subdomain</th>
                                            <th>Status</th>
                                            <th class="text-end">Updated</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($restaurants as $restaurant)
                                            @php
                                                $logoPath = $restaurant->logo;
                                                $logoUrl = $logoPath
                                                    ? (Str::startsWith($logoPath, ['http://', 'https://'])
                                                        ? $logoPath
                                                        : asset('storage/' . ltrim($logoPath, '/')))
                                                    : '';
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="flex-shrink-0">
                                                            <span
                                                                class="avatar avatar-sm rounded-circle bg-light d-inline-flex align-items-center justify-content-center">
                                                                @if ($logoUrl)
                                                                    <img src="{{ $logoUrl }}" alt="logo"
                                                                        class="rounded-circle"
                                                                        style="height:32px;width:32px;object-fit:cover;">
                                                                @else
                                                                    <i class="fas fa-store text-muted"></i>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div>{{ $restaurant->name }}</div>
                                                            <div class="small text-muted">ID: #{{ $restaurant->id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $restaurant->domain }}</td>
                                                <td>{{ $restaurant->subdomain ?? '—' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $restaurant->status === 'active' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                                        {{ Str::headline($restaurant->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end text-muted small">
                                                    {{ optional($restaurant->updated_at)->diffForHumans() ?? '—' }}
                                                </td>
                                                <td class="text-end">
                                                    <a class="btn btn-sm btn-outline-primary"
                                                        href="{{ route('admin.restaurant.edit', $restaurant->id) }}">
                                                        <i class="fas fa-pen me-1"></i>Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Quick Stats</h6>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Restaurant status</span>
                            <span
                                class="fw-semibold {{ optional($currentRestaurant)->status === 'active' ? 'text-success' : 'text-warning' }}">
                                {{ optional($currentRestaurant)->status ? Str::headline($currentRestaurant->status) : 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Last updated</span>
                            <span class="fw-semibold text-muted">
                                {{ optional(optional($currentRestaurant)->updated_at)->diffForHumans() ?? '—' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total staff</span>
                            <span class="fw-semibold text-primary">{{ $totalStaff }}</span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Staff Preview</h6>
                        @if (!$currentRestaurant)
                            <p class="text-muted small mb-0">Create your restaurant to start assigning staff members.</p>
                        @elseif ($currentRestaurant->staff->isEmpty())
                            <p class="text-muted small mb-0">No staff members assigned yet.</p>
                        @else
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="fw-semibold">{{ $currentRestaurant->name }}</div>
                                <span
                                    class="badge bg-secondary-subtle text-secondary">{{ $currentRestaurant->staff->count() }}
                                    staff</span>
                            </div>
                            <ul class="list-unstyled small mb-0">
                                @foreach ($currentRestaurant->staff->take(5) as $member)
                                    <li class="d-flex justify-content-between py-1 border-bottom">
                                        <span>{{ $member->name }}</span>
                                        <span class="text-muted">{{ $member->role ?? 'Staff' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if ($currentRestaurant->staff->count() > 5)
                                <div class="small text-muted mt-2">+ {{ $currentRestaurant->staff->count() - 5 }} more
                                    staff members</div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Account</h6>
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar rounded-circle bg-primary text-white fw-semibold"
                                style="width:48px;height:48px;display:inline-flex;align-items:center;justify-content:center;">
                                {{ Str::of($admin->name)->substr(0, 2)->upper() }}
                            </span>
                            <div>
                                <div class="fw-semibold">{{ $admin->name }}</div>
                                <div class="small text-muted">{{ $admin->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-8 mt-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Weekly Schedule</h5>
                            <p class="text-muted small mb-0">Set opening and closing times for each day.</p>
                        </div>
                    </div>
                    <div class="card-body">

                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $scheduleMap = $schedules->keyBy('day_of_week');
                        @endphp

                        <form method="POST" action="{{ route('admin.restaurant.schedules.update') }}"
                            class="table-responsive">
                            @csrf

                            <table class="table align-middle mb-3">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:140px;">Day</th>
                                        <th style="width:180px;">Opening</th>
                                        <th style="width:180px;">Closing</th>
                                        <th style="width:100px;">Open?</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($days as $index => $day)
                                        @php
                                            $rec = $scheduleMap->get($day);

                                            $isOpen = $rec->is_open ?? true;
                                            $opening = $rec->opening_time ?? '09:00';
                                            $closing = $rec->closing_time ?? '17:00';
                                        @endphp

                                        <tr>
                                            <td class="fw-semibold">
                                                {{ $day }}
                                                <input type="hidden" name="day_of_week[]" value="{{ $day }}">
                                            </td>

                                            <td>
                                                <input type="time" class="form-control form-control-sm"
                                                    name="opening_time[]" value="{{ $opening }}"
                                                    {{ !$isOpen ? 'readonly' : '' }}>
                                            </td>

                                            <td>
                                                <input type="time" class="form-control form-control-sm"
                                                    name="closing_time[]" value="{{ $closing }}"
                                                    {{ !$isOpen ? 'readonly' : '' }}>
                                            </td>

                                            <td>
                                                <div class="form-check form-switch">

                                                    <!-- Always submit 0 if unchecked -->
                                                    <input type="hidden" name="is_open[{{ $index }}]"
                                                        value="0">

                                                    <!-- Checkbox (sends 1 if checked) -->
                                                    <input class="form-check-input schedule-open-toggle" type="checkbox"
                                                        name="is_open[{{ $index }}]" value="1"
                                                        data-row-index="{{ $index }}"
                                                        {{ $isOpen ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Schedule
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            @endsection



            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toggles = document.querySelectorAll('.schedule-open-toggle');

                        toggles.forEach(toggle => {
                            toggle.addEventListener('change', function() {
                                const row = this.closest('tr');
                                const timeInputs = row.querySelectorAll('input[type="time"]');

                                if (this.checked) {
                                    timeInputs.forEach(inp => inp.readOnly = false);
                                } else {
                                    timeInputs.forEach(inp => inp.readOnly = true);
                                }
                            });
                        });


                        @if (session('success'))
                            createToast(@json(session('success')), 'success');
                        @endif
                        @if (session('error'))
                            createToast(@json(session('error')), 'error');
                        @endif
                        @if ($errors->any())
                            createToast(@json($errors->first()), 'error');
                        @endif
                    });
                </script>
            @endpush
