    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-utensils brand-icon"></i>
            <span class="brand-text">FoodHub</span>
        </div>
        <nav class="sidebar-nav">
            <ul class="list-unstyled">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.restaurant.edit') }}"
                        class="nav-link {{ request()->routeIs('admin.restaurant.edit') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Restaurants</span>
                    </a>
                </li>
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-toggle="staff-dropdown">
                        <i class="fas fa-users-cog"></i>
                        <span>Staff</span>
                        <i class="fas fa-chevron-down dropdown-caret" style="margin-left:auto"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu" id="staff-dropdown"
                        style="display:none; padding-left:10px;">
                        <li class="nav-item">
                            <a href="{{ route('restaurant.staff.index') }}" class="nav-link">
                                <i class="fas fa-user-friends"></i>
                                <span>All Staff</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Delivery men route may not exist yet; point to a safe placeholder route or hash --}}
                            <a href="#" class="nav-link">
                                <i class="fas fa-motorcycle"></i>
                                <span>Delivery Men</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-page="menus">
                        <i class="fas fa-book-open"></i>
                        <span>Menus</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-page="orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-page="customers">
                        <i class="fas fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-page="settings">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-page="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <script>
        // Simple toggle for sidebar dropdowns (no dependency on Bootstrap JS)
        (function() {
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('[data-toggle="staff-dropdown"]');
                if (toggle) {
                    e.preventDefault();
                    const id = toggle.getAttribute('data-toggle');
                    const menu = document.getElementById(id);
                    if (!menu) return;
                    menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' :
                        'none';
                    return;
                }

                // Click outside the dropdown should close it
                const dropdown = document.getElementById('staff-dropdown');
                if (dropdown && !e.target.closest('#staff-dropdown') && !e.target.closest(
                        '[data-toggle="staff-dropdown"]')) {
                    dropdown.style.display = 'none';
                }
            });
        })();
    </script>
