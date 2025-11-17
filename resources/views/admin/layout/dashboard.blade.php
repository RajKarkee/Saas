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
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New
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

    <!-- Content Cards -->
    <div class="row">
        {{-- Pending card widened and restyled --}}
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
                <div class="content-card-header">
                    <h5 class="mb-0"><i class="fas fa-stream me-2"></i>Recent Activities</h5>
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

@endsection

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    {{-- Custom quick style improvements for modern look --}}
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
    {{-- DataTables & dependencies --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

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
                    // data[3] is the Restaurant Status column - update index if markup changes
                    var statusCell = $('td:eq(3)', row);
                    if (statusCell.length && statusCell.text().toLowerCase().includes('pending')) {
                        $(row).addClass('table-warning');
                    }
                }
            });
        });
    </script>
@endpush
