@extends('restaurant.layout.app')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">{{ isset($addon) ? 'Edit' : 'Add' }} Addon</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form
                    action="{{ isset($addon) ? route('admin.restaurant.menu.items.addons.update', $addon->id) : route('admin.restaurant.menu.items.addons.store', $item->id) }}"
                    method="POST">
                    @csrf
                    @if (isset($addon))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Menu Item</label>
                            <input type="text" class="form-control" value="{{ $item->name }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Additional Price</label>
                            <input type="number" name="additional_price"
                                class="form-control @error('additional_price') is-invalid @enderror"
                                value="{{ old('additional_price', $addon->additional_price ?? '') }}">
                            @error('additional_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $addon->name ?? '') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Max Select</label>
                            <input type="number" name="max_select"
                                class="form-control @error('max_select') is-invalid @enderror"
                                value="{{ old('max_select', $addon->max_select ?? 1) }}">
                            @error('max_select')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="is_available" value="1"
                                    class="form-check-input @error('is_available') is-invalid @enderror" id="is_available"
                                    {{ old('is_available', $addon->is_available ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Available</label>
                                @error('is_available')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary">{{ isset($addon) ? 'Update' : 'Create' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
