    <div class="bottom-nav">
        <a href="{{ route('restaurant.delivery.index') }}"
            class="nav-item {{ request()->routeIs('restaurant.delivery.index') ? 'active' : '' }}" ">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('restaurant.delivery.ongoing') }}" class="nav-item {{ request()->routeIs('restaurant.delivery.ongoing') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Deliveries</span>
        </a>
        <a href="#" class="nav-item" data-section="routes">
            <i class="fas fa-route"></i>
            <span>Routes</span>
        </a>
        <a href="#" class="nav-item" data-section="history">
            <i class="fas fa-history"></i>
            <span>History</span>
        </a>
        <a href="{{ route('restaurant.delivery.profile') }}" class="nav-item" {{ request()->routeIs('restaurant.delivery.profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
    </div>
