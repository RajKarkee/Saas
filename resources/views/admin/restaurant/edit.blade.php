@extends('admin.layout.app')
@section('title', 'Restaurants/edit')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Restaurant</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Restaurant</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container admin-form">
        <div class="content-card">
            <div class="content-card-body">
                <form action="{{ route('super_admin.restaurant.update', $restaurant->id) }}" method="POST"
                    enctype="multipart/form-data" id="editRestaurantForm">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="owner_id" class="form-label">Owner (Admin)</label>
                                    @php
                                        $currentOwnerId = old('owner_id', $restaurant->owner_id ?? null);
                                    @endphp
                                    <select name="owner_id" id="owner_id" class="form-select" size="4">
                                        {{-- If the restaurant has an owner that's not in the $admin list, show it first so Select2 can display it --}}
                                        @if (!empty($restaurant->owner))
                                            <option value="{{ $restaurant->owner->id }}"
                                                {{ $currentOwnerId == $restaurant->owner->id ? 'selected' : '' }}>
                                                {{ $restaurant->owner->name ?? ($restaurant->owner->email ?? 'Admin #' . $restaurant->owner->id) }}
                                            </option>
                                        @endif

                                        @if (!empty($admin) && $admin->count())
                                            @foreach ($admin as $a)
                                                <option value="{{ $a->id }}"
                                                    {{ $currentOwnerId == $a->id ? 'selected' : '' }}>
                                                    {{ $a->name ?? ($a->email ?? 'Admin #' . $a->id) }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No available admins</option>
                                        @endif
                                    </select>
                                    @error('owner_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Restaurant Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        placeholder="Restaurant full name" value="{{ old('name', $restaurant->name) }}">
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="domain" class="form-label">Custom Domain</label>
                                    <input type="text" class="form-control" id="domain" name="domain"
                                        placeholder="example.com" value="{{ old('domain', $restaurant->domain) }}">
                                    @error('domain')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="subdomain" class="form-label">Subdomain (optional)</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain"
                                        placeholder="shop-name" value="{{ old('subdomain', $restaurant->subdomain) }}">
                                    @error('subdomain')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active"
                                            {{ old('status', $restaurant->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $restaurant->status) == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="pending"
                                            {{ old('status', $restaurant->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>

                                    </select>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('super_admin.restaurant.index') }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>

                        {{-- <div class="col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Restaurant Logo</h6>
                                <div class="text-center mb-2">
                                    @php
                                        $logo = $restaurant->logo ?? null;
                                        $logoUrl = $logo
                                            ? (Str::startsWith($logo, ['http://', 'https://'])
                                                ? $logo
                                                : asset('storage/' . $logo))
                                            : asset('images/default-avatar.png');
                                    @endphp
                                    <img src="{{ $logoUrl }}" id="previewImg" alt="logo"
                                        style="width:100%;height:180px;object-fit:cover;">
                                </div>
                                <div class="d-grid gap-2">
                                    <input type="file" id="imageInput" name="logo" accept="image/*"
                                        style="display:none">
                                    <button type="button" class="btn btn-outline-secondary" id="browseBtn">Browse</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="removeImage">Remove</button>
                                    <div class="small text-muted" id="imageInfo">
                                        {{ $logo ? basename($logo) : 'No file selected' }}</div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const imageInput = document.getElementById('imageInput');
            const previewImg = document.getElementById('previewImg');
            const browseBtn = document.getElementById('browseBtn');
            const removeBtn = document.getElementById('removeImage');
            const imageInfo = document.getElementById('imageInfo');

            function setPreview(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imageInfo.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                };
                reader.readAsDataURL(file);
            }

            browseBtn.addEventListener('click', function() {
                imageInput.click();
            });

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('File too large. Max 5MB');
                    return;
                }
                setPreview(file);
            });

            removeBtn.addEventListener('click', function() {
                previewImg.src =
                    "data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20300'><rect%20fill='%23f6f7fb'%20width='100%25'%20height='100%25'/><text%20x='50%25'%20y='50%25'%20dominant-baseline='middle'%20text-anchor='middle'%20fill='%23999'%20font-family='Arial'%20font-size='20'>No%20image</text></svg>";
                imageInput.value = '';
                imageInfo.textContent = 'No file selected';
            });
        })();
    </script>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#owner_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Select Owner --',
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush

    <style>
        .image-preview {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
    </style>
@endsection
