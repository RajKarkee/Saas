@extends('admin.layout.app')
@section('title', 'Edit Restaurant Admin')
@section('content')
    @php
        // Use the relation names provided by your controller: adminPhoto and adminRestaurant
        // avoid calling ->first() on null by using optional()
        $photoRecord = $admin->adminPhoto ?? optional($admin->adminPhotos)->first();
        $photoPath = $photoRecord->photo_path ?? ($admin->image ?? null);
        $imageUrl = $photoPath
            ? (\Illuminate\Support\Str::startsWith($photoPath, ['http://', 'https://'])
                ? $photoPath
                : asset('storage/' . $photoPath))
            : asset('images/default-avatar.png'); // ensure public/images/default-avatar.png exists
    @endphp
    <div class="container admin-form">
        <div class="content-card">
            <div class="content-card-body">
                <form action="{{ route('super_admin.admins.update', $admin->id) }}" method="POST"
                    enctype="multipart/form-data" id="editadminForm">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-lg-8">


                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="owner" class="form-label">Owner Name</label>
                                    <input type="text" class="form-control" id="owner" name="name" required
                                        placeholder="Owner full name" value="{{ old('name', $admin->name ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Owner Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        placeholder="owner@example.com" value="{{ old('email', $admin->email ?? '') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active"
                                            {{ old('status', $admin->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="pending"
                                            {{ old('status', $admin->status ?? '') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="inactive"
                                            {{ old('status', $admin->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="restaurant_count" class="form-label">Restaurant Count</label>
                                    <input type="number" class="form-control" id="restaurant_count" name="restaurant_count"
                                        placeholder="Enter Restaurant Count"
                                        value="{{ old('restaurant_count', $admin->adminRestaurant->restaurant_count ?? '') }}">
                                </div>
                            </div>


                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('super_admin.admins.index') }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Restaurant Image</h6>

                                <div id="imageDrop" class="image-drop p-3 text-center" tabindex="0">
                                    <input type="file" id="imageInput" name="image" accept="image/*"
                                        style="display:none">
                                    <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                                    <div class="image-preview" id="imagePreview">
                                        <img src="{{ $imageUrl }}" alt="{{ $admin->name ?? 'Admin' }}" id="previewImg"
                                            data-initial="{{ $imageUrl }}"
                                            data-default="{{ asset('images/default-avatar.png') }}">
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <script>
        (function() {
            const imageDrop = document.getElementById('imageDrop');
            const imageInput = document.getElementById('imageInput');
            const previewImg = document.getElementById('previewImg');
            const browseBtn = document.getElementById('browseBtn');
            const removeBtn = document.getElementById('removeImage');
            const imageInfo = document.getElementById('imageInfo');
            const placeholder = previewImg.dataset.initial || '';
            const defaultImg = previewImg.dataset.default || '';
            const removeFlag = document.getElementById('removeImageFlag');

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
                // selecting a new file should unset remove flag
                if (removeFlag) removeFlag.value = '0';
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
                // set preview to default image (not the initial/original)
                previewImg.src = defaultImg || placeholder;
                // clear file input
                imageInput.value = '';
                // set remove flag so server deletes the image
                if (removeFlag) removeFlag.value = '1';
                imageInfo.textContent = 'No file selected';
            });

            // Toast UI (reuse the same as add)
            function createToast(message, type = 'error') {
                const container = document.getElementById('toastContainer') || (() => {
                    const c = document.createElement('div');
                    c.id = 'toastContainer';
                    c.style.position = 'fixed';
                    c.style.top = '1rem';
                    c.style.right = '1rem';
                    c.style.zIndex = 9999;
                    document.body.appendChild(c);
                    return c;
                })();

                const t = document.createElement('div');
                t.className = 'toast-item ' + type;
                t.style.minWidth = '240px';
                t.style.marginTop = '8px';
                t.style.padding = '12px 14px';
                t.style.borderRadius = '8px';
                t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.08)';
                t.style.color = '#fff';
                t.style.fontSize = '14px';
                t.style.display = 'flex';
                t.style.alignItems = 'center';
                t.style.justifyContent = 'space-between';
                if (type === 'error') t.style.background = 'linear-gradient(90deg,#ff6b6b,#ff3b3b)';
                else t.style.background = 'linear-gradient(90deg,#34d399,#10b981)';

                const span = document.createElement('div');
                span.textContent = message;
                t.appendChild(span);

                const close = document.createElement('button');
                close.textContent = '×';
                close.style.background = 'transparent';
                close.style.border = 'none';
                close.style.color = 'rgba(255,255,255,0.9)';
                close.style.fontSize = '18px';
                close.style.cursor = 'pointer';
                close.addEventListener('click', () => t.remove());
                t.appendChild(close);

                container.appendChild(t);
                setTimeout(() => t.remove(), 6000);
            }

            // AJAX submit for edit
            document.getElementById('editadminForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                if (!form.action || form.action === '#') {
                    createToast('No action configured for this form', 'error');
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
                            createToast(first, 'error');
                        } else {
                            createToast(data.error || 'An error occurred', 'error');
                        }
                        return;
                    }
                    createToast(data.message || 'Admin updated', 'success');
                    if (data.redirect) setTimeout(() => window.location = data.redirect, 900);
                }).catch(err => {
                    console.error(err);
                    createToast('Failed to submit. Try again.', 'error');
                });
            });
        })();
    </script>
@endsection
