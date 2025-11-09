    <div class="mobile-header">
        <div class="header-content">
            <div class="header-left">
                <h1 id="headerTitle">Dashboard</h1>
                <p id="headerSubtitle">Track your deliveries</p>
            </div>
            <div class="header-right">
                <button class="theme-toggle-btn" id="themeToggle" title="Toggle Theme">
                    <i class="fas fa-sun sun-icon"></i>
                    <i class="fas fa-moon moon-icon"></i>
                </button>
                <button class="header-btn" id="notificationBtn">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                @php
                    $staffId = session('staff_id') ?? session('staffId');
                    $staff = null;
                    $staffPhotos = null;
                    if ($staffId) {
                        $staff = DB::table('staff')->where('id', $staffId)->first();
                        if ($staff) {
                            $staffPhotos = DB::table('staff_photos')->where('staff_id', $staff->id)->first();
                        }
                    }
                    $photoUrl = null;

                @endphp
                <div class="user-avatar">
                    @if ($staffPhotos && isset($staffPhotos->photo_url))
                        <img src="{{ asset('storage/' . $staffPhotos->photo_url) }}" alt="Profile">
                    @else
                        {{ strtoupper(substr($staff->name ?? 'U', 0, 1)) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
