@extends('staff.layout.app')
@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="mb-4">
            <h2 class="mb-1"><i class="fas fa-cog me-2"></i>Account Settings</h2>
            <p class="text-muted mb-0">Manage your profile information and security settings</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Profile Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Profile Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('restaurant.staff.setting') }}">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $staff->name) }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Field -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">
                                    Phone Number
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $staff->phone) }}"
                                        placeholder="e.g., +234 800 000 0000">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role Field (Read-only) -->
                            <div class="mb-3">
                                <label for="role" class="form-label fw-semibold">
                                    Role
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-badge"></i>
                                    </span>
                                    <input type="text" class="form-control" id="role"
                                        value="{{ $staff->role == 2 ? 'Delivery Person' : ($staff->role == 1 ? 'Manager' : 'Staff Member') }}"
                                        readonly disabled style="background-color: #e9ecef;">
                                </div>
                                <small class="text-muted">Role cannot be changed. Contact administrator for role
                                    updates.</small>
                            </div>

                            <hr class="my-4">

                            <!-- Password Section -->
                            <h6 class="mb-3"><i class="fas fa-lock me-2"></i>Change Password</h6>
                            <p class="text-muted small mb-3">Leave blank to keep current password</p>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter new password (min. 8 characters)">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Password must be at least 8 characters long</small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Summary Card -->
            <div class="col-lg-4">
                <!-- Avatar Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            @if (isset($photo->photo_url) && $photo->photo_url)
                                <img src="{{ asset($photo->photo_url) }}" alt="{{ $staff->name }}"
                                    class="rounded-circle"
                                    style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f8f9fa;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                    style="width: 120px; height: 120px; font-size: 48px; border: 4px solid #f8f9fa;">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h5 class="mb-1">{{ $staff->name }}</h5>
                        <p class="text-muted mb-2">{{ $staff->email }}</p>
                        <span class="badge bg-primary">
                            {{ $staff->role == 2 ? 'Delivery Person' : ($staff->role == 1 ? 'Manager' : 'Staff Member') }}
                        </span>
                    </div>
                </div>

                <!-- Account Info Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Staff ID</small>
                            <div class="fw-semibold">#{{ $staff->id }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Restaurant ID</small>
                            <div class="fw-semibold">#{{ $staff->restaurant_id }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Member Since</small>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($staff->created_at)->format('M d, Y') }}
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Last Updated</small>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($staff->updated_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: #dee2e6;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #86b7fe;
            background-color: #e7f1ff;
        }

        .input-group:focus-within .form-control {
            border-color: #86b7fe;
        }

        .form-control:disabled,
        .form-control[readonly] {
            cursor: not-allowed;
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-dismiss success message after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>
@endsection
