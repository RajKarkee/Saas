@extends('admin.layout.app')
@section('title', 'Restaurants/add')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Add Restaurant</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Restaurant</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container admin-form">
        <div class="content-card">
            <div class="content-card-body">
                <form action="{{ route('super_admin.restaurant.store') }}" method="POST" enctype="multipart/form-data"
                    id="addRestaurantForm">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="owner_id" class="form-label">Owner (Admin)</label>
                                    <select name="owner_id" id="owner_id" class="form-select" size="4">
                                        <option value="">-- Select Owner --</option>
                                        @if (!empty($admin) && $admin->count())
                                            @foreach ($admin as $a)
                                                <option value="{{ $a->id }}"
                                                    {{ old('owner_id') == $a->id ? 'selected' : '' }}>
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
                                        placeholder="Restaurant full name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="domain" class="form-label">Custom Domain</label>
                                    <input type="text" class="form-control" id="domain" name="domain"
                                        placeholder="example.com" value="{{ old('domain') }}">
                                    @error('domain')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="subdomain" class="form-label">Subdomain (optional)</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain"
                                        placeholder="shop-name" value="{{ old('subdomain') }}">
                                    @error('subdomain')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Add Restaurant</button>
                                <a href="{{ route('super_admin.restaurant.index') }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>

                        {{-- <div class="col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Restaurant Logo</h6>

                                <div id="imageDrop" class="image-drop p-3 text-center" tabindex="0">
                                    <input type="file" id="imageInput" name="logo" accept="image/*"
                                        style="display:none">
                                    <div class="image-preview" id="imagePreview">
                                        <img src="data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20300'><rect%20fill='%23f6f7fb'%20width='100%25'%20height='100%25'/><text%20x='50%25'%20y='50%25'%20dominant-baseline='middle'%20text-anchor='middle'%20fill='%23999'%20font-family='Arial'%20font-size='20'>No%20image</text></svg>"
                                            alt="preview" id="previewImg">
                                    </div>
                                    <p class="small text-muted mt-2">Drag & drop an image here or <button type="button"
                                            class="btn btn-link p-0" id="browseBtn">browse</button></p>
                                    <p class="small text-muted">Supported: JPG, PNG. Max 5MB.</p>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="removeImage">Remove</button>
                                    </div>
                                    <div>
                                        <span class="text-muted small" id="imageInfo">No file selected</span>
                                    </div>
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
            const imageDrop = document.getElementById('imageDrop');
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

            imageDrop.addEventListener('click', function() {
                imageInput.click();
            });

            browseBtn.addEventListener('click', function(e) {
                e.preventDefault();
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

            // Drag & drop
            ['dragenter', 'dragover'].forEach(evt => {
                imageDrop.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                imageDrop.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('dragover');
                });
            });

            imageDrop.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const file = dt.files[0];
                if (file) {
                    imageInput.files = dt.files; // populate input for form submission
                    imageInput.dispatchEvent(new Event('change'));
                }
            });

            removeBtn.addEventListener('click', function() {
                previewImg.src =
                    "data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20300'><rect%20fill='%23f6f7fb'%20width='100%25'%20height='100%25'/><text%20x='50%25'%20y='50%25'%20dominant-baseline='middle'%20text-anchor='middle'%20fill='%23999'%20font-family='Arial'%20font-size='20'>No%20image</text></svg>";
                imageInput.value = '';
                imageInfo.textContent = 'No file selected';
            });

            // AJAX submit (graceful fallback to normal submit)
            document.getElementById('addRestaurantForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                if (!formData.get('name')) {
                    alert('Please enter restaurant name');
                    return;
                }

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: formData
                }).then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        if (res.status === 422 && data.errors) {
                            const first = Object.values(data.errors)[0][0];
                            alert(first);
                        } else {
                            alert(data.error || 'An error occurred');
                        }
                        return;
                    }
                    alert(data.message || 'Restaurant created');
                    if (data.redirect) setTimeout(() => window.location = data.redirect, 900);
                }).catch(err => {
                    console.error(err);
                    alert('Failed to submit. Try again.');
                });
            });
        })();
    </script>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Select2 on owner dropdown with search (searches local options from $admin)
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
        .image-drop {
            border: 2px dashed #e9ecef;
            border-radius: .6rem;
            background: #fff;
            cursor: pointer;
            transition: border-color .15s ease, background .15s ease;
        }

        .image-drop:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.08);
        }

        .image-drop.dragover {
            background: #f8f9ff;
            border-color: #4e73df;
        }

        .image-preview {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: .5rem;
            background: #f6f7fb;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
@endsection
