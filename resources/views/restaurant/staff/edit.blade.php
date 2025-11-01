@extends('restaurant.layout.app')

@section('content')
    <div class="container mt-5">
        <h3>Edit Staff Member</h3>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Staff Information</h5>
                <a href="{{ route('admin.restaurant.staff.index') }}" class="btn btn-secondary">Back to Staff List</a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.restaurant.staff.update', $staff->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $staff->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone (optional)</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $staff->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password (optional)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Leave blank to keep current password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-control @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="Manager"
                                            {{ old('role', $staff->role) == 'Manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="Staff"
                                            {{ old('role', $staff->role) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="Delivery Person"
                                            {{ old('role', $staff->role) == 'Delivery Person' ? 'selected' : '' }}>Delivery
                                            Person</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="active"
                                            {{ old('status', $staff->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="inactive"
                                            {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary">Update Staff Member</button>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Staff Photo</h6>

                                <div id="imageDrop" class="image-drop p-3 text-center" tabindex="0">
                                    <input type="file" id="imageInput" name="photo" accept="image/*"
                                        style="display:none">
                                    <div class="image-preview" id="imagePreview">
                                        @php
                                            $photoPath = $staff->photo ?? null;
                                            $imageUrl = $photoPath
                                                ? (Illuminate\Support\Str::startsWith($photoPath, [
                                                    'http://',
                                                    'https://',
                                                ])
                                                    ? $photoPath
                                                    : asset('storage/' . $staff->photo_url))
                                                : asset('images/default-avatar.png');
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="preview" id="previewImg">
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
                                    @error('photo')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
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
        })();
    </script>

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
