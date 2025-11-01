@extends('restaurant.layout.app')

@section('content')
    @php
        use Illuminate\Support\Str;
        // Prefer a provided $setting, otherwise try $restaurant->settings if available
        $setting = $setting ?? ($restaurant->settings ?? null);
        $restaurantId = $restaurant->id ?? null;
        $logoUrl = '';
        if ($setting && !empty($setting->logo)) {
            $logoUrl = Str::startsWith($setting->logo, ['http://', 'https://'])
                ? $setting->logo
                : asset('storage/' . ltrim($setting->logo, '/'));
        }
    @endphp

    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Restaurant Settings</h2>
                <p class="text-muted mb-0">Update contact info, logo, and map link for your restaurant. Values are retained
                    after submit.</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.restaurant.settings') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- Use PUT via hidden field if you prefer: @method('PUT') --}}

                    <input type="hidden" name="restaurant_id" value="{{ old('restaurant_id', $restaurantId) }}">

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Address</label>
                                <input type="text" name="address" id="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address', optional($setting)->address) }}"
                                    placeholder="123 Main St, City, Country">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">Phone</label>
                                    <input type="text" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', optional($setting)->phone) }}" placeholder="+1 555 555 5555">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Contact Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', optional($setting)->email) }}"
                                        placeholder="contact@restaurant.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="map_url" class="form-label fw-semibold">Map URL</label>
                                <input type="url" name="map_url" id="map_url"
                                    class="form-control @error('map_url') is-invalid @enderror"
                                    value="{{ old('map_url', optional($setting)->map_url) }}"
                                    placeholder="https://maps.google.com/...?q=...">
                                @error('map_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Restaurant Logo</label>
                            <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                                <div class="text-center mb-3">
                                    <img src="{{ $logoUrl ?: asset('storage/' . $setting->logo) }}" id="logoPreview"
                                        class="img-fluid rounded" style="max-height: 150px; object-fit: contain;"
                                        alt="Logo">
                                </div>
                                <div>
                                    <label class="btn btn-outline-secondary w-100" for="logoInput">
                                        <i class="fas fa-upload me-2"></i>Choose Logo
                                    </label>
                                    <input type="file" class="d-none" name="logo" id="logoInput" accept="image/*">
                                    <div class="small text-muted mt-2" id="logoFileName">
                                        {{ old('logo_file_name', optional($setting)->logo ? basename(optional($setting)->logo) : 'No file selected') }}
                                    </div>
                                    <div class="form-text">Note: backend file saving may not be implemented yet.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('logoInput');
            const preview = document.getElementById('logoPreview');
            const nameEl = document.getElementById('logoFileName');

            input && input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        preview.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                    nameEl.textContent = file.name;
                } else {
                    nameEl.textContent = 'No file selected';
                }
            });
        });
    </script>
@endsection
