@extends('admin.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
            {{-- show super admin name --}}
            @if (isset($superAdmin))
                <small class="text-muted">Welcome, {{ $superAdmin->name }}</small>
            @endif
        </div>
        {{-- Add Item Button --}}
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="fas fa-plus me-2"></i>Add Item
            </button>
        </div>
    </div>

    {{-- show buttons/stats greeting --}}
    @php
        $pendingCount = isset($restaurant) ? $restaurant->where('status', 'pending')->count() : 0;
        $totalRestaurants = $restaurantCount ?? 0;
        $recentActivities = isset($restaurant) ? $restaurant->sortByDesc('created_at')->take(6) : collect();
    @endphp

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">$45,678</div>
            <div class="stat-label">Revenue</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-value">567</div>
            <div class="stat-label">Orders</div>
        </div>

        {{-- Show dynamic restaurants count from controller --}}
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-value">{{ $totalRestaurants }}</div>
            <div class="stat-label">Restaurants</div>
        </div>

        {{-- New: Pending Restaurants --}}
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Pending Restaurants</div>
        </div>
    </div>


    <div class="row">

        <div class="col-lg-6">
            <div class="content-card shadow-sm">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-hourglass-half me-2"></i>Pending Requests</h5>
                    <div class="text-end">
                        <div class="small text-muted">Open approvals</div>
                        <div class="h6 mb-0"><span class="badge bg-warning text-dark">{{ $pendingCount }}</span></div>
                    </div>
                </div>
                <div class="content-card-body" style="max-height: 420px; overflow-y: auto;">
                    @if (isset($restaurant) && $pendingCount)
                        @foreach ($restaurant->where('status', 'pending')->take(8) as $pending)
                            <div class="d-flex align-items-center border-bottom py-2">
                                <div class="me-3">
                                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center"
                                        style="width:48px;height:48px;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $pending->name }}</strong>
                                            <div class="text-muted small">Owner: {{ $pending->owner_name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="small text-muted">
                                                {{ optional($pending->created_at)->diffForHumans() }}</div>
                                            <div class="mt-1">
                                                <a href="#" class="btn btn-sm btn-success me-1" title="Approve"><i
                                                        class="fas fa-check"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-danger" title="Reject"><i
                                                        class="fas fa-times"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-end mt-2">
                            <a href="#" class="small">View all pending</a>
                        </div>
                    @else
                        <div class="text-muted">No pending restaurants.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent activities column --}}
        <div class="col-lg-6">
            <div class="content-card shadow-sm">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-stream me-2"></i>Recent Activities</h5>
                    {{-- Activity Log Settings Button --}}
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#activityLogModal">
                        <i class="fas fa-cog"></i> Settings
                    </button>
                </div>
                <div class="content-card-body" style="max-height: 420px; overflow-y: auto;">
                    @if ($recentActivities->count())
                        @foreach ($recentActivities as $act)
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width:44px;height:44px;">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $act->name }}</strong>
                                            <div class="small text-muted">
                                                @if (!empty($act->owner_name))
                                                    Owner: {{ $act->owner_name }} &middot;
                                                @endif
                                                Status: <span
                                                    class="fw-semibold">{{ ucfirst($act->status ?? 'N/A') }}</span>
                                            </div>
                                        </div>
                                        <div class="text-muted small text-end">
                                            {{ optional($act->created_at)->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted">No recent activity found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurants Table -->
    <div class="content-card mt-4">
        <div class="content-card-header">
            <h5><i class="fas fa-list me-2"></i>Restaurants</h5>
            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="content-card-body">
            <div class="table-responsive">
                @if (isset($restaurant) && $restaurant->count())
                    <table id="restaurants-table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Owner</th>
                                <th>Restaurant Status</th>
                                <th>Owner Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restaurant as $rest)
                                @php $rstatus = strtolower($rest->status ?? 'unknown'); @endphp
                                <tr>
                                    <td>#{{ $rest->id }}</td>
                                    <td>{{ $rest->name }}</td>
                                    <td>{{ $rest->owner_name ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge
                                            @if ($rstatus === 'active') bg-success
                                            @elseif($rstatus === 'pending') bg-warning text-dark
                                            @else bg-secondary @endif">
                                            {{ ucfirst($rest->status ?? 'Unknown') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php $ostatus = strtolower($rest->owner_status ?? 'unknown'); @endphp
                                        <span
                                            class="badge
                                            @if ($ostatus === 'active') bg-success
                                            @elseif($ostatus === 'pending') bg-warning text-dark
                                            @else bg-secondary @endif">
                                            {{ ucfirst($rest->owner_status ?? 'Unknown') }}
                                        </span>
                                    </td>
                                    <td>{{ optional($rest->created_at)->format('Y-m-d') ?? '—' }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i
                                                class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i
                                                class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-muted">No restaurants found.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Item Modal --}}
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" placeholder="Enter item name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Item</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Log Settings Modal --}}
    <div class="modal fade" id="activityLogModal" tabindex="-1" aria-labelledby="activityLogModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityLogModalLabel">Activity Log Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Configure how system activities are recorded.</p>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Log Retention (Days)</label>
                            <input type="number" class="form-control" value="30">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="logErrors" checked>
                            <label class="form-check-label" for="logErrors">Log System Errors</label>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="logActions" checked>
                            <label class="form-check-label" for="logActions">Log User Actions</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">Save Settings</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .stats-grid {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .stat-card {
            padding: 12px;
            border-radius: 8px;
            min-width: 180px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .stat-card .stat-icon {
            font-size: 24px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: #fff;
        }

        .stat-card.success .stat-icon {
            background: #28a745;
        }

        .stat-card.warning .stat-icon {
            background: #ffc107;
        }

        .stat-card.danger .stat-icon {
            background: #e55353;
        }

        .stat-card.info .stat-icon {
            background: #17a2b8;
        }

        .content-card {
            border-radius: 10px;
            background: #fff;
            padding: 0;
        }

        .content-card-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e9ecef;
        }

        .content-card-body {
            padding: 16px;
        }

        .table-modern th {
            background: #f8f9fa;
        }

        .table-warning {
            background: #fff7e6 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#restaurants-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthChange: false,
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                createdRow: function(row, data, dataIndex) {

                    var statusCell = $('td:eq(3)', row);
                    if (statusCell.length && statusCell.text().toLowerCase().includes('pending')) {
                        $(row).addClass('table-warning');
                    }
                }
            });
        });
    </script>
@endpush
