@extends('restaurant.layout.app')

@php
    use Illuminate\Support\Str;
    $logoUrl = '';
    if (!empty($restaurant->logo)) {
        $logoUrl = Str::startsWith($restaurant->logo, ['http://', 'https://'])
            ? $restaurant->logo
            : asset('storage/' . ltrim($restaurant->logo, '/'));
    }
@endphp

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Restaurant Settings</h2>
                <p class="text-muted mb-0">Update your restaurant's profile, domain, and status. Logo upload is available but
                    not processed yet.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.restaurant.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label fw-semibold">Restaurant Name</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="E.g. Sunrise Diner" value="{{ old('name', $restaurant->name) }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="domain" class="form-label fw-semibold">Domain</label>
                                    <input type="text" name="domain" id="domain"
                                        class="form-control @error('domain') is-invalid @enderror"
                                        placeholder="restaurant.com" value="{{ old('domain', $restaurant->domain) }}"
                                        required>
                                    <div class="form-text">Must be unique across the platform.</div>
                                    @error('domain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="subdomain" class="form-label fw-semibold">Subdomain</label>
                                    <input type="text" name="subdomain" id="subdomain"
                                        class="form-control @error('subdomain') is-invalid @enderror"
                                        placeholder="myrestaurant" value="{{ old('subdomain', $restaurant->subdomain) }}">
                                    <div class="form-text">Optional if you use a custom domain.</div>
                                    @error('subdomain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Contact Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="owner@restaurant.com" value="{{ old('email', $restaurant->email) }}"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active"
                                            {{ old('status', $restaurant->status) === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $restaurant->status) === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Restaurant Logo</label>
                            <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                                <div class="text-center mb-3">
                                    <img src="{{ $logoUrl ?: asset('images/default-avatar.png') }}" id="logoPreview"
                                        class="img-fluid rounded" style="max-height: 150px; object-fit: contain;"
                                        alt="Restaurant Logo">
                                </div>
                                <div>
                                    <label class="btn btn-outline-secondary w-100" for="logo">
                                        <i class="fas fa-upload me-2"></i>Choose Logo
                                    </label>
                                    <input type="file" class="d-none" name="logo" id="logo" accept="image/*">
                                    <div class="small text-muted mt-2" id="logoFileName">
                                        {{ $restaurant->logo ? basename($restaurant->logo) : 'No file selected' }}
                                    </div>
                                    <div class="form-text">Note: Upload UI only. Saving the file is disabled for now.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logoPreview');
            const logoFileName = document.getElementById('logoFileName');

            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        logoPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                    logoFileName.textContent = file.name;
                } else {
                    // reset to original image if no file
                    logoPreview.src = logoPreview.getAttribute('data-original') || logoPreview.src;
                    logoFileName.textContent = 'No file selected';
                }
            });
        });
    </script>
@endsection
