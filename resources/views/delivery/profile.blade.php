@extends('delivery.layout.app')
@section('content')
    <div class="card-container">
        <div class="card p-4">
            <form action="/delivery/profile" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">
                <div class="row g-3">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            @php
                                // Prefer staff photo from staff_photos table, fallback to staff->photo or a placeholder
                                $photoUrl = null;
                                if (!empty($staffPhotos->path ?? null)) {
                                    $photoUrl = Storage::url($staffPhotos->path);
                                } elseif (!empty($staff->photo ?? null)) {
                                    $photoUrl = Storage::url($staff->photo);
                                } else {
                                    $photoUrl = asset('images/default-avatar.png');
                                }
                            @endphp

                            <div class="profile-preview" style="width:160px;margin:0 auto">
                                <img id="profilePreviewImg" src="{{ asset('storage/' . $staffphoto->photo_url) }}"
                                    alt="profile"
                                    style="width:160px;height:160px;object-fit:cover;border-radius:12px;border:1px solid rgba(15,23,42,0.06)">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="btn btn-outline-secondary btn-sm">
                                Change photo
                                <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none">
                            </label>
                            <button type="button" id="removePhotoBtn"
                                class="btn btn-link btn-sm text-danger">Remove</button>
                        </div>
                        <p class="muted small">PNG, JPG up to 2MB</p>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full name</label>
                                <input name="name" type="text" class="form-control"
                                    value="{{ old('name', $staff->name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control"
                                    value="{{ old('email', $staff->email ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input name="phone" type="text" class="form-control"
                                    value="{{ old('phone', $staff->phone ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="Delivery Man" readonly>
                            </div>

                            {{-- <div class="col-12">
                                        <label class="form-label">Bio / Notes</label>
                                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $staff->bio ?? '') }}</textarea>
                                    </div> --}}

                            <div class="col-md-6">
                                <label class="form-label">Password (leave blank to keep)</label>
                                <input name="password" type="password" class="form-control">
                            </div>

                            <div class="col-md-6 d-flex align-items-end justify-content-end">
                                <div>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <a href="#" class="btn btn-outline-secondary ms-2" id="cancelProfile">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
