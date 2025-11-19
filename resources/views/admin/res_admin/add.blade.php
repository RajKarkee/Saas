@extends('admin.layout.app')
@section('title', 'Restaurent_Admins/add')
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
                <form action="{{ route('super_admin.admins.store') }}" method="POST" enctype="multipart/form-data"
                    id="addadminForm">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-8">


                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="owner" class="form-label">Owner Name</label>
                                    <input type="text" class="form-control" id="owner" name="name" required
                                        placeholder="Owner full name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Owner Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        placeholder="owner@example.com">
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
                                        <option value="active">Active</option>
                                        <option value="pending">Pending</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <lable for="Restuarant Count">Restuarant Count*</lable>
                                    <input type="number" class="form-control" id="restaurant_count" name="restaurant_count"
                                        placeholder="Enter Restuarant Count">
                                </div>
                            </div>


                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Add Admin</button>
                                <a href="{{ route('super_admin.admins.index') }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Admin Image</h6>

                                <div id="imageDrop" class="image-drop p-3 text-center" tabindex="0">
                                    <input type="file" id="imageInput" name="image" accept="image/*"
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <style>
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
    </style> --}}

    <script>
        (function() {
            const imageDrop = document.getElementById('imageDrop');
            const imageInput = document.getElementById('imageInput');
            const previewImg = document.getElementById('previewImg');
            const browseBtn = document.getElementById('browseBtn');
            const removeBtn = document.getElementById('removeImage');
            const imageInfo = document.getElementById('imageInfo');
            const count = document.getElementById('restaurant_count');

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

            // Toast UI
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

            // AJAX submit
            // document.getElementById('addadminForm').addEventListener('submit', function(e) {
            //     e.preventDefault();
            //     const form = this;
            //     const formData = new FormData(form);
            //     const restaurantCount = parseInt(document.getElementById('restaurant_count').value, 10);
            //     if (isNaN(restaurantCount) || restaurantCount < 1) {
            //         createToast('Restaurant count must be at least 1', 'error');
            //         return;
            //     }
            //     // basic client-side checks
            //     if (!formData.get('name') || !formData.get('email')) {
            //         createToast('Please fill required fields', 'error');
            //         return;
            //     }

            //     fetch(form.action, {
            //         method: 'POST',
            //         headers: {
            //             'X-Requested-With': 'XMLHttpRequest',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
            //                 'content')
            //         },
            //         body: formData
            //     }).then(async res => {
            //         const data = await res.json().catch(() => ({}));
            //         if (!res.ok) {
            //             if (res.status === 422 && data.errors) {
            //                 const first = Object.values(data.errors)[0][0];
            //                 createToast(first, 'error');
            //             } else {
            //                 createToast(data.error || 'An error occurred', 'error');
            //             }
            //             return;
            //         }
            //         createToast(data.message || 'Admin created', 'success');
            //         if (data.redirect) setTimeout(() => window.location = data.redirect, 900);
            //     }).catch(err => {
            //         console.error(err);
            //         createToast('Failed to submit. Try again.', 'error');
            //     });
            // });
        })();
    </script>

    <style>
        /* small toast item styling for accessibility */
        #toastContainer .toast-item {
            opacity: 0.98;
        }
    </style>
@endsection
