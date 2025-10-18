@extends('restaurant.welcome')
@section('content')
    <div class="container mt-20">
        <h3>Manage your Staff</h3>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Staff Members</h5>
                <a href="{{ route('restaurant.staff.create') }}" class="btn btn-primary float-end">Add New Staff</a>
            </div>
            <div class="card-body">
                {{-- @if ($staff->isEmpty())
                    <p>No staff members found. Click "Add New Staff" to create one.</p>
                @else --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                            @foreach ($staff as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ ucfirst($member->role) }}</td>
                                    <td>
                                        <a href="{{ route('restaurant.staff.edit', $member->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('restaurant.staff.destroy', $member->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                </table>
                {{-- @endif --}}
            </div>
        </div>
    </div>
@endsection
