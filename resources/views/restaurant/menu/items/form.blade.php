@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">{{ isset($item) ? 'Edit' : 'Add' }} Menu Item</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form
                    action="{{ isset($item) ? route('admin.restaurant.menu.items.update', $item->id) : route('admin.restaurant.menu.items.store', $category->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" value="{{ $category->name ?? '' }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $item->price ?? '') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $item->name ?? '') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="stock_quantity"
                                class="form-control @error('stock_quantity') is-invalid @enderror"
                                value="{{ old('stock_quantity', $item->stock_quantity ?? 0) }}">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_available" value="1" class="form-check-input"
                                    id="is_available"
                                    {{ old('is_available', $item->is_available ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Available</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Item image</h6>

                                <div id="imageDrop" class="image-drop p-3 text-center" tabindex="0">
                                    <input type="file" id="imageInput" name="image" accept="image/*"
                                        style="display:none">

                                    <div class="image-preview" id="imagePreview">
                                        @php($existingImage = isset($item_images) && count($item_images) ? $item_images[0] : null)
                                        <img src="{{ $existingImage && $existingImage->image_url ? asset('storage/' . $existingImage->image_url) : "data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20300'><rect%20fill='%23f6f7fb'%20width='100%25'%20height='100%25'/><text%20x='50%25'%20y='50%25'%20dominant-baseline='middle'%20text-anchor='middle'%20fill='%23999'%20font-family='Arial'%20font-size='20'>No%20image</text></svg>" }}"
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
                                    @error('image')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card p-3">
                                <h6 class="mb-3">Image_alt</h6>

                                <div id="imageDropAlt" class="image-drop p-3 text-center" tabindex="0">
                                    <input type="file" id="imageInputAlt" name="image_alt" accept="image/*"
                                        style="display:none">
                                    <div class="image-preview" id="imagePreviewAlt">
                                        @php($existingImage = isset($item_images) && count($item_images) ? $item_images[0] : null)
                                        <img src="{{ $existingImage && $existingImage->image_alt ? asset('storage/' . $existingImage->image_alt) : "data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20300'><rect%20fill='%23f6f7fb'%20width='100%25'%20height='100%25'/><text%20x='50%25'%20y='50%25'%20dominant-baseline='middle'%20text-anchor='middle'%20fill='%23999'%20font-family='Arial'%20font-size='20'>No%20image</text></svg>" }}"
                                            alt="preview" id="previewImgAlt">
                                    </div>
                                    <p class="small text-muted mt-2">Drag & drop an image here or <button type="button"
                                            class="btn btn-link p-0" id="browseBtnAlt">browse</button></p>
                                    <p class="small text-muted">Supported: JPG, PNG. Max 5MB.</p>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="removeImageAlt">Remove</button>
                                    </div>
                                    <div>
                                        <span class="text-muted small" id="imageInfoAlt">No file selected</span>
                                    </div>
                                    @error('image_alt')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <button class="btn btn-primary">{{ isset($item) ? 'Update' : 'Create' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
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

            // --- Alt (mobile/small-screen) image handlers ---
            const imageDropAlt = document.getElementById('imageDropAlt');
            const imageInputAlt = document.getElementById('imageInputAlt');
            const previewImgAlt = document.getElementById('previewImgAlt');
            const browseBtnAlt = document.getElementById('browseBtnAlt');
            const removeBtnAlt = document.getElementById('removeImageAlt');
            const imageInfoAlt = document.getElementById('imageInfoAlt');

            function setPreviewAlt(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImgAlt.src = e.target.result;
                    imageInfoAlt.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                };
                reader.readAsDataURL(file);
            }

            imageDropAlt.addEventListener('click', function() {
                imageInputAlt.click();
            });

            browseBtnAlt.addEventListener('click', function(e) {
                e.preventDefault();
                imageInputAlt.click();
            });

            imageInputAlt.addEventListener('change', function() {
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
                setPreviewAlt(file);
            });

            // Drag & drop for alt
            ['dragenter', 'dragover'].forEach(evt => {
                imageDropAlt.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                imageDropAlt.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('dragover');
                });
            });

            imageDropAlt.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const file = dt.files[0];
                if (file) {
                    imageInputAlt.files = dt.files; // populate input for form submission
                    imageInputAlt.dispatchEvent(new Event('change'));
                }
            });

            removeBtnAlt.addEventListener('click', function() {
                previewImgAlt.src =
                    "data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20300'><rect%20fill='%23f6f7fb'%20width='100%25'%20height='100%25'/><text%20x='50%25'%20y='50%25'%20dominant-baseline='middle'%20text-anchor='middle'%20fill='%23999'%20font-family='Arial'%20font-size='20'>No%20image</text></svg>";
                imageInputAlt.value = '';
                imageInfoAlt.textContent = 'No file selected';
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
@endpush
