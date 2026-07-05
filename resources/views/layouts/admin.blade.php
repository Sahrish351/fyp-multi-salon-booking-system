<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Glamora Admin')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @stack('styles')
    
    <style>
        /* ================================================================
                   BASE VARIABLES – PINK THEME (Like Client Dashboard)
                   ================================================================ */
        :root {
            --bg: #fff5f9;
            --white: #FFFFFF;
            --pink: #E91E8C;
            --pink-dark: #c2185b;
            --pink-light: #fce4ec;
            --pink-bg: #fff0f7;
            --text: #1a1c1d;
            --text-mid: #4a4452;
            --text-lt: #9c8b7e;
            --border: #fce4ec;
            --shadow: 0 1px 8px rgba(233,30,140,0.07);
            --shadow-md: 0 4px 20px rgba(233,30,140,0.1);
            --sidebar-w: 260px;
            --red: #DC2626;
            --green: #16A34A;
            --blue: #2563EB;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            overflow-x: hidden;
        }
        
        /* Layout */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* ================================================================
                   SIDEBAR – PINK GRADIENT
                   ================================================================ */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #E91E8C 0%, #c2185b 100%);
            border-right: 1px solid rgba(255,255,255,0.1);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 200;
        }
        
        .sidebar-header {
            padding: 28px 20px 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        
        .sidebar-header p {
            font-size: 0.65rem;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.6);
            margin-top: 4px;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 20px 12px;
        }
        
        .nav-group {
            margin-bottom: 24px;
        }
        
        .nav-group-title {
            font-size: 0.65rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            padding: 8px 12px;
            font-weight: 600;
        }
        
        .nav-item {
            margin-bottom: 4px;
        }
        
        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s;
        }
        
        .nav-item a i {
            width: 20px;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }
        
        .nav-item:hover a {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        
        .nav-item:hover a i {
            color: #fff;
        }
        
        .nav-item.active a {
            background: rgba(255,255,255,0.20);
            color: #fff;
            font-weight: 600;
        }
        
        .nav-item.active a i {
            color: #fff;
        }
        
        /* Sidebar Logout */
        .sidebar-nav .nav-item:last-child {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
            padding-top: 16px;
        }
        
        /* ================================================================
                   MAIN CONTENT
                   ================================================================ */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* ================================================================
                   TOP NAVBAR – PINK THEME
                   ================================================================ */
        .admin-topbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .topbar-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 40px;
            padding: 8px 18px;
            width: 280px;
        }
        
        .topbar-search input {
            border: none;
            background: none;
            outline: none;
            font-size: 0.8rem;
            width: 100%;
            font-family: inherit;
            color: var(--text);
        }
        
        .topbar-search input::placeholder {
            color: #aaa;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .live-badge {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--green);
            background: #ecfdf5;
            padding: 4px 12px;
            border-radius: 40px;
        }
        
        .notif-icon {
            position: relative;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--text-mid);
        }
        
        .notif-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: var(--pink);
            color: white;
            font-size: 0.6rem;
            padding: 2px 6px;
            border-radius: 20px;
        }
        
        .profile-area {
            position: relative;
            cursor: pointer;
        }
        
        .profile-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #E91E8C, #c2185b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .profile-dropdown {
            position: absolute;
            top: 48px;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            width: 200px;
            display: none;
            z-index: 200;
            border: 1px solid var(--border);
        }
        
        .profile-dropdown.show {
            display: block;
        }
        
        .profile-dropdown a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: var(--text);
            font-size: 0.8rem;
            border-bottom: 1px solid var(--border);
        }
        
        .profile-dropdown a:hover {
            background: var(--pink-bg);
        }
        
        /* ================================================================
                   ADMIN BODY
                   ================================================================ */
        .admin-body {
            padding: 28px 32px;
            flex: 1;
        }
        
        /* ================================================================
                   CARDS – PINK THEME
                   ================================================================ */
        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: all .3s ease;
        }
        
        .card:hover {
            border-color: var(--pink);
            box-shadow: 0 8px 25px rgba(233,30,140,0.08);
            transform: translateY(-2px);
        }
        
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
        }
        
        .card-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
        }
        
        /* ================================================================
                   BUTTONS – PINK THEME
                   ================================================================ */
        .btn-primary {
            background: linear-gradient(135deg, #E91E8C, #c2185b);
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(233,30,140,0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #d81b60, #a31545);
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(233,30,140,0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid var(--pink);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.7rem;
            cursor: pointer;
            color: var(--pink);
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-outline:hover {
            background: var(--pink);
            border-color: var(--pink);
            color: #fff;
        }
        
        .btn-secondary {
            background: #f0f0f0;
            border: 1px solid #ddd;
            color: #555;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        /* ================================================================
                   TABLES – PINK THEME
                   ================================================================ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.65rem;
            text-transform: uppercase;
            color: var(--text-mid);
            background: var(--bg);
            font-weight: 600;
        }
        
        .data-table td {
            padding: 12px 16px;
            font-size: 0.8rem;
            border-top: 1px solid var(--border);
            color: var(--text-mid);
        }
        
        .data-table tr:hover td {
            background: var(--pink-bg);
        }
        
        .data-table tbody tr {
            cursor: pointer;
            transition: background 0.2s;
        }
        
        /* ================================================================
                   BADGES – PINK THEME
                   ================================================================ */
        .badge {
            display: inline-flex;
            padding: 3px 10px;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #ecfdf5;
            color: var(--green);
        }
        
        .badge-warning {
            background: #fffbeb;
            color: #f59e0b;
        }
        
        .badge-danger {
            background: #fef2f2;
            color: var(--red);
        }
        
        .badge-info {
            background: #eff6ff;
            color: var(--blue);
        }
        
        /* ================================================================
                   PAGINATION – PINK THEME
                   ================================================================ */
        .pagination-wrapper {
            padding: 1rem;
            border-top: 1px solid var(--border);
        }
        
        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }
        
        .pagination .page-item.active .page-link {
            background: var(--pink);
            border-color: var(--pink);
            color: #fff;
        }
        
        .pagination .page-link:hover {
            color: var(--pink);
        }
        
        /* ================================================================
                   FORM INPUTS – PINK THEME
                   ================================================================ */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 0.2rem rgba(233,30,140,0.15);
        }
        
        /* ================================================================
                   ALERTS – PINK THEME
                   ================================================================ */
        .alert-success {
            background: #ecfdf5;
            border-color: #bbf7d0;
            color: #166534;
        }
        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
        .alert-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }
        .alert-info {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1e40af;
        }
        
        /* ================================================================
                   MODALS – PINK THEME
                   ================================================================ */
        .modal-content {
            border: 1px solid var(--border);
            border-radius: 20px;
        }
        .modal-header {
            border-bottom: 1px solid var(--border);
        }
        .modal-footer {
            border-top: 1px solid var(--border);
        }
        
        /* ================================================================
                   DROPDOWNS – PINK THEME
                   ================================================================ */
        .dropdown-menu {
            border: 1px solid var(--border);
        }
        .dropdown-item:hover {
            background: var(--pink-bg);
            color: var(--pink);
        }
        
        /* ================================================================
                   RESPONSIVE
                   ================================================================ */
        @media (max-width: 900px) {
            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed;
                transition: 0.2s;
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-body {
                padding: 20px;
            }
            .topbar-search {
                width: 180px;
            }
        }
        
        /* Sidebar toggle for mobile (optional) */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-mid);
            cursor: pointer;
        }
        @media (max-width: 900px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <!-- ============================================================
    SIDEBAR
    ============================================================ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h1>Glamora</h1>
            <p>ADMIN PANEL</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-group">
                <div class="nav-group-title">MAIN</div>
                <div class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a>
                </div>
            </div>
            
            <div class="nav-group">
                <div class="nav-group-title">MANAGEMENT</div>
                <div class="nav-item {{ request()->routeIs('admin.salon-requests.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.salon-requests.index') }}"><i class="fas fa-store"></i> Salon Requests</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.salons.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.salons.index') }}"><i class="fas fa-building"></i> All Salons</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.owners.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.owners.index') }}"><i class="fas fa-user-tie"></i> Owners</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.clients.index') }}"><i class="fas fa-users"></i> Clients</a>
                </div>
                
                <div class="nav-item {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.appointments.index') }}"><i class="fas fa-calendar"></i> Appointments</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.index') }}"><i class="fas fa-credit-card"></i> Payments</a>
                </div>
                
                <div class="nav-item {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.complaints.index') }}"><i class="fas fa-exclamation-triangle"></i> Complaints</a>
                </div>
            </div>
            
            <div class="nav-group">
                <div class="nav-group-title">SYSTEM</div>
                <div class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.notifications.index') }}"><i class="fas fa-bell"></i> Notifications</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.audit-logs.index') }}"><i class="fas fa-history"></i> Audit Logs</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faqs.index') }}"><i class="fas fa-question-circle"></i> FAQs</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.hero-sliders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.hero-sliders.index') }}"><i class="fas fa-images"></i> Hero Sliders</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contact-messages.index') }}"><i class="fas fa-envelope"></i> Contact Messages</a>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}"><i class="fas fa-sliders-h"></i> Settings</a>
                </div>
            </div>
            
            <!-- Logout -->
            <div class="nav-item">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </nav>
    </aside>
    
    <!-- ============================================================
    MAIN CONTENT
    ============================================================ -->
    <main class="admin-main">
        <!-- Topbar -->
        <div class="admin-topbar">
            <div class="topbar-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search anything..." id="globalSearch">
            </div>
            <div class="topbar-right">
                <span class="live-badge"><i class="fas fa-circle" style="font-size: 6px;"></i> LIVE</span>
                <div class="notif-icon" id="notifIcon">
                    <i class="far fa-bell"></i>
                    <span class="notif-badge">{{ auth()->user()->unreadNotifications->count() ?? 0 }}</span>
                </div>
                <div class="profile-area" id="profileBtn">
                    <div class="profile-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="{{ route('admin.settings.index') }}"><i class="fas fa-user"></i> My Profile</a>
                        <a href="{{ route('admin.settings.index') }}"><i class="fas fa-cog"></i> Account Settings</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="admin-body">
            @yield('content')
        </div>
    </main>
</div>

<!-- ============================================================
SCRIPTS
============================================================ -->
<script>
    // Profile Dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    if(profileBtn) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });
        document.addEventListener('click', () => profileDropdown?.classList.remove('show'));
    }
    
    // Global Search
    const searchInput = document.getElementById('globalSearch');
    if(searchInput) {
        searchInput.addEventListener('keyup', (e) => {
            if(e.key === 'Enter') {
                alert('Searching for: ' + e.target.value);
            }
        });
    }
    
    // Notification Click
    const notifIcon = document.getElementById('notifIcon');
    if(notifIcon) {
        notifIcon.addEventListener('click', () => {
            window.location.href = "{{ route('admin.notifications.index') }}";
        });
    }
</script>

@stack('scripts')
</body>
</html>