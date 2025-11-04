   <div class="top-nav">
       <div class="nav-left">
           <button class="toggle-sidebar" id="toggleSidebar">
               <i class="fas fa-bars"></i>
           </button>
           {{-- <div class="search-bar">
               <input type="text" placeholder="Search deliveries, customers...">
               <i class="fas fa-search"></i>
           </div> --}}
       </div>

       <div class="nav-right">
           <button class="theme-toggle" id="themeToggle">
               <i class="fas fa-sun sun-icon"></i>
               <i class="fas fa-moon moon-icon"></i>
           </button>
           <button class="nav-btn">
               <i class="fas fa-bell"></i>
               <span class="badge">3</span>
           </button>
           <button class="nav-btn">
               <i class="fas fa-envelope"></i>
               <span class="badge">5</span>
           </button>
           <div class="user-profile" id="userProfile">
               <div class="user-avatar">
                   @if (!empty($staffPhotos) && !empty($staffPhotos->photo_url))
                       <img src="{{ asset($staffPhotos->photo_url) }}" alt="{{ $staff->name ?? 'Avatar' }}"
                           class="avatar-img" />
                   @elseif(!empty($staff) && !empty($staff->name))
                       {{-- show initials if no photo --}}
                       @php
                           $parts = explode(' ', trim($staff->name));
                           $initials = strtoupper(substr($parts[0], 0, 1));
                           if (count($parts) > 1) {
                               $initials .= strtoupper(substr($parts[1], 0, 1));
                           }
                       @endphp
                       <span class="avatar-initials">{{ $initials ?? 'AR' }}</span>
                   @else
                       AR
                   @endif
               </div>
               <div class="user-info">
                   <div class="user-name">{{ $staff->name }}</div>
                   <div class="user-role">Delivery Driver</div>
               </div>
               <i class="fas fa-chevron-down"></i>

               <!-- Profile Dropdown -->
               <div class="profile-dropdown">
                   <div class="dropdown-header">
                       <div class="user-name">{{ $staff->name }}</div>
                       <div class="user-role">Delivery Driver</div>
                   </div>
                   <div class="dropdown-menu-list">
                       <a href="#" class="dropdown-item" data-section="profile">
                           <i class="fas fa-user"></i>
                           <span>My Profile</span>
                       </a>
                       <a href="#" class="dropdown-item" data-section="settings">
                           <i class="fas fa-cog"></i>
                           <span>Settings</span>
                       </a>
                       <a href="#" class="dropdown-item" data-section="earnings">
                           <i class="fas fa-wallet"></i>
                           <span>Earnings</span>
                       </a>
                       <div class="dropdown-divider"></div>
                       <a href="#" class="dropdown-item" onclick="logout(); return false;">
                           <i class="fas fa-sign-out-alt"></i>
                           <span>Logout</span>
                       </a>
                   </div>
               </div>
           </div>
       </div>
   </div>
