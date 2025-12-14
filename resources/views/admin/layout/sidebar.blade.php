    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="fas fa-crown brand-icon"></i>
            <span class="brand-text">Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <ul class="list-unstyled">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('super_admin.index') }}"
                        class="nav-link {{ request()->routeIs('super_admin.index') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="Admins">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Admins</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="{{ route('super_admin.admins.index') }}" class="nav-link">
                                <i class="fas fa-list"></i>
                                <span class="nav-text">All Admins</span>
                            </a>
                        </li>
                        <!-- Nested Admin/Staff Dropdown -->
                        {{-- <li class="nav-item nav-dropdown nested-dropdown">
                            <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="users-roles">
                                <i class="fas fa-user-cog"></i>
                                <span class="nav-text">Admin & Staff</span>
                                <i class="fas fa-chevron-down dropdown-caret"></i>
                            </a>
                            <ul class="list-unstyled nav-dropdown-menu" id="users-roles">
                                <li class="nav-item">
                                    <a href=" # " class="nav-link">
                                        <i class="fas fa-user-shield"></i>
                                        <span class="nav-text">Admins</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href=" # " class="nav-link">
                                        <i class="fas fa-user-tie"></i>
                                        <span class="nav-text">Staff</span>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('super_admin.admins.create') }}" class="nav-link">
                                <i class="fas fa-user-plus"></i>
                                <span class="nav-text">Add admins</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-shield"></i>
                                <span class="nav-text">Roles</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Users Dropdown -->
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="users">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Users</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-list"></i>
                                <span class="nav-text">All Users</span>
                            </a>
                        </li>
                        <!-- Nested Admin/Staff Dropdown -->
                        {{-- <li class="nav-item nav-dropdown nested-dropdown">
                            <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="users-roles">
                                <i class="fas fa-user-cog"></i>
                                <span class="nav-text">Admin & Staff</span>
                                <i class="fas fa-chevron-down dropdown-caret"></i>
                            </a>
                            <ul class="list-unstyled nav-dropdown-menu" id="users-roles">
                                <li class="nav-item">
                                    <a href=" # " class="nav-link">
                                        <i class="fas fa-user-shield"></i>
                                        <span class="nav-text">Admins</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href=" # " class="nav-link">
                                        <i class="fas fa-user-tie"></i>
                                        <span class="nav-text">Staff</span>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-plus"></i>
                                <span class="nav-text">Add User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-shield"></i>
                                <span class="nav-text">Roles</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Restaurants Dropdown -->
                <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="restaurants">
                        <i class="fas fa-store"></i>
                        <span class="nav-text">Restaurants</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="{{ route('super_admin.restaurant.index') }}" class="nav-link">
                                <i class="fas fa-list"></i>
                                <span class="nav-text">All Restaurants</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('super_admin.restaurant.create') }}" class="nav-link">
                                <i class="fas fa-plus-circle"></i>
                                <span class="nav-text">Add Restaurant</span>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('super_admin.restaurant.pending') }}" class="nav-link">
                                <i class="fas fa-clock"></i>
                                <span class="nav-text">Pending Approval</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <!-- Orders -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="nav-text">Orders</span>
                    </a>
                </li> --}}

                <!-- Reports Dropdown -->
                {{-- <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="reports">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-dollar-sign"></i>
                                <span class="nav-text">Sales Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span class="nav-text">Customer Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-line"></i>
                                <span class="nav-text">Revenue Report</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <!-- Settings Dropdown -->
                {{-- <li class="nav-item nav-dropdown">
                    <a href="#" class="nav-link nav-dropdown-toggle" data-dropdown="settings">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <ul class="list-unstyled nav-dropdown-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-sliders-h"></i>
                                <span class="nav-text">General</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-credit-card"></i>
                                <span class="nav-text">Payment Gateway</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-envelope"></i>
                                <span class="nav-text">Email Settings</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <!-- Support -->
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-life-ring"></i>
                        <span class="nav-text">Support</span>
                    </a>
                </li> --}}
                <li class="nav-item">

                    <a href="{{ route('super_admin.logout') }}" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logoutForm').submit();   ">
                        <form type="logout" action="{{ route('super_admin.logout') }}" method="POST" id="logoutForm">
                            @csrf
                        </form>
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
