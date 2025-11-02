@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">{{ isset($category) ? 'Edit' : 'Add' }} Category</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form
                    action="{{ isset($category) ? route('admin.restaurant.menu.categories.update', $category->id) : route('admin.restaurant.menu.categories.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($category))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name ?? '') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position</label>
                            <input type="number" name="position"
                                class="form-control @error('position') is-invalid @enderror"
                                value="{{ old('position', $category->position ?? 0) }}">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                            {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <button class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
