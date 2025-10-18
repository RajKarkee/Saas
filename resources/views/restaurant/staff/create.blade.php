@extends('restaurant.welcome')

@section('content')
    <div class="container mt-5">
        <h3>Add New Staff Member</h3>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Staff Information</h5>
                <a href="{{ route('restaurant.staff.index') }}" class="btn btn-secondary">Back to Staff List</a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('restaurant.staff.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone (optional)</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone') }}">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="manager">Manager</option>
                            <option value="waiter">Staff_Member</option>
                            <option value="chef">Delivery_Person</option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Add Staff Member</button>
                </form>
            </div>
        </div>
    </div>
@endsection
