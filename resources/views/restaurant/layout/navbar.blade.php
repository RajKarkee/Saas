  {{-- @php
      $user = Auth::guard('admin')->user();
      $imageUrl = DB::table('admin__photos')->where('admin_id', $user->id)->first();
      $image = $imageUrl ? asset('storage/' . $imageUrl->photo_path) : null;

  @endphp --}}

  <div class="top-navbar">
      <div class="navbar-left">
          <button class="menu-toggle" id="menuToggle">
              <i class="fas fa-bars"></i>
          </button>
          {{-- <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search..." id="globalSearch">
            </div> --}}
      </div>
      <div class="navbar-right">
          <div class="navbar-icon">
              <i class="fas fa-bell"></i>
              <span class="badge-notification">3</span>
          </div>
          <div class="profile-dropdown">
              <img src="{{ $adminImage ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional($adminUser)->name ?? 'Admin') . '&background=4e73df&color=fff' }}"
                  alt="Profile" class="profile-img">
              {{-- <div class="d-none d-md-block">
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                    @if (Auth::user()->role == 0)
                        <div style="font-size: 0.75rem; color: var(--secondary-color);">Administrator</div>
                    @elseif(Auth::user()->role == 1)
                        <div style="font-size: 0.75rem; color: var(--secondary-color);">Restaurant Admin</div>
                    @elseif(Auth::user()->role == 2)
                        <div style="font-size: 0.75rem; color: var(--secondary-color);">Staff</div>
                    @endif
                </div> --}}
              <i class="fas fa-chevron-down d-none d-md-block"></i>
          </div>
          <div class="profile-menu" id="profileMenu">
              <a href="{{ route('admin.profile') }}">Profile</a>
              <a href="{{ route('admin.restaurant.settings') }}">Settings</a>

              <div style="height:1px;background:#e9ecef;margin:0.25rem 0"></div>
              <a href="#" id="logoutLink">Logout</a>
          </div>
      </div>
  </div>
