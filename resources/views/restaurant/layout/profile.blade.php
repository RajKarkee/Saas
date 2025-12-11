@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mt-2">
                    <div class="card-header mt-3">
                        <h4 class="card-title">Profile Settings</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Profile Photo</label>
                                <div id="photo-drop-area" class="drop-area border rounded p-3 text-center">
                                    <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                                        <div class="text-center mb-3">
                                            <img src="{{ $adminImage ?: asset('images/default-avatar.png') }}"
                                                id="imagePreview" class="img-fluid rounded"
                                                style="max-height: 150px; object-fit: contain;" alt=" Profile Photo">
                                        </div>
                                        <div>
                                            <label class="btn btn-outline-secondary w-100" for="image">
                                                <i class="fas fa-upload me-2"></i>Choose Profile Photo
                                            </label>
                                            <input type="file" class="d-none" name="image" id="image"
                                                accept="image/*">
                                            <div class="small text-muted mt-2" id="imageFileName">
                                                {{ $adminImage ?? 'No file selected' }}
                                            </div>
                                            @error('image')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                            @error('photo')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                            {{-- <div class="form-text">Note: Upload UI only. Saving the file is disabled for
                                                now.</div> --}}
                                        </div>
                                    </div>
                                </div>
                                <input type="file" class="form-control d-none" id="photo" name="photo"
                                    accept="image/*">
                                <small class="text-muted d-block mt-2">Supported: JPG, PNG. Max ~2MB.</small>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $admin->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $admin->email) }}"readonly>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Leave blank to keep current password">
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Leave blank if you do not want to change the
                                            password.</small>
                                        <!-- Replace the simple file input with a drop area + preview -->
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('image');
            const logoPreview = document.getElementById('imagePreview');
            const logoFileName = document.getElementById('imageFileName');

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
    <style>
        #photo-drop-area {
            border: 2px dashed #ced4da;
            cursor: pointer;
            background-color: #fff;
        }

        #photo-drop-area.dragover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
    </style>
