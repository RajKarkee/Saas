    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <h5 class="text-white">Admin Panel</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('restaurant.staff.index') }}"
                    href="{{ route('restaurant.staff.index') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#users"><i class="fas fa-users"></i> Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#reports"><i class="fas fa-chart-bar"></i> Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('restaurant.staff.setting') ? 'active' : '' }}"
                    href="{{ route('restaurant.staff.setting') }}"><i class="fas fa-cog"></i>
                    Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('restaurant.staff.logout') }}"><i class="fas fa-sign-out-alt"></i>
                    Logout</a>
            </li>
        </ul>
    </nav>
