@extends('admin.layout.app')
@section('content')
    <div class="container-fluid py-4">
        {{-- Toast container for flash messages --}}
        <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position:fixed;top:1rem;right:1rem;z-index:9999;">
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-semibold">Settings</h2>
                <p class="text-muted mb-0">Manage your admin settings.</p>
            </div>
        </div>
    </div>
@endsection
