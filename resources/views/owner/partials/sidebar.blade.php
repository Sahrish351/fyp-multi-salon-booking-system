

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="ownerSidebar">

    {{-- Brand / Logo --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-stars"></i>
        </div>
        <div class="brand-text">
            <h1>GlowAura</h1>
            <span>Luxury Salon</span>
        </div>
    </div>

    {{-- Navigation Links --}}
    <ul class="sidebar-nav">

        <li>
            <a href="{{ route('owner.dashboard') }}"
               class="nav-link-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill nav-ico"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.salon-profile') }}"
               class="nav-link-item {{ request()->routeIs('owner.salon-profile') ? 'active' : '' }}">
                <i class="bi bi-shop nav-ico"></i>
                <span>Salon Profile</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.services') }}"
               class="nav-link-item {{ request()->routeIs('owner.services') ? 'active' : '' }}">
                <i class="bi bi-scissors nav-ico"></i>
                <span>Services</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.categories') }}"
               class="nav-link-item {{ request()->routeIs('owner.categories') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 nav-ico"></i>
                <span>Categories</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.team-members') }}"
               class="nav-link-item {{ request()->routeIs('owner.team-members') ? 'active' : '' }}">
                <i class="bi bi-people-fill nav-ico"></i>
                <span>Team Members</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.time-slots') }}"
               class="nav-link-item {{ request()->routeIs('owner.time-slots') ? 'active' : '' }}">
                <i class="bi bi-clock-fill nav-ico"></i>
                <span>Time Slots</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.appointments') }}"
               class="nav-link-item {{ request()->routeIs('owner.appointments') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill nav-ico"></i>
                <span>Appointments</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.waitlist') }}"
               class="nav-link-item {{ request()->routeIs('owner.waitlist') ? 'active' : '' }}">
                <i class="bi bi-list-task nav-ico"></i>
                <span>Waitlist</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.payments') }}"
               class="nav-link-item {{ request()->routeIs('owner.payments') ? 'active' : '' }}">
                <i class="bi bi-credit-card-fill nav-ico"></i>
                <span>Payments</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.sales-analytics') }}"
               class="nav-link-item {{ request()->routeIs('owner.sales-analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow nav-ico"></i>
                <span>Sales Analytics</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.clients') }}"
               class="nav-link-item {{ request()->routeIs('owner.clients') ? 'active' : '' }}">
                <i class="bi bi-person-circle nav-ico"></i>
                <span>Clients</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.reviews') }}"
               class="nav-link-item {{ request()->routeIs('owner.reviews') ? 'active' : '' }}">
                <i class="bi bi-star-fill nav-ico"></i>
                <span>Reviews</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.gallery') }}"
               class="nav-link-item {{ request()->routeIs('owner.gallery') ? 'active' : '' }}">
                <i class="bi bi-images nav-ico"></i>
                <span>Gallery</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.notifications') }}"
               class="nav-link-item {{ request()->routeIs('owner.notifications') ? 'active' : '' }}">
                <i class="bi bi-bell-fill nav-ico"></i>
                <span>Notifications</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.reports') }}"
               class="nav-link-item {{ request()->routeIs('owner.reports') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill nav-ico"></i>
                <span>Reports</span>
            </a>
        </li>

        <div class="nav-divider"></div>

        <li>
            <a href="{{ route('owner.settings') }}"
               class="nav-link-item {{ request()->routeIs('owner.settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill nav-ico"></i>
                <span>Settings</span>
            </a>
        </li>

        <li>
            <a href="{{ route('owner.logout') ?? '#' }}"
               class="nav-link-item">
                <i class="bi bi-box-arrow-right nav-ico"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>
</aside>
