{{-- ============================================================ --}}
{{-- FILE: resources/views/layouts/client.blade.php               --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="en" data-theme="{{ Auth::user()->theme ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Account') — Beauty Blush Salons</title>

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    @stack('styles')

    <style>
        :root { 
            --client-pink: #FF6B9D; 
            --client-light: #fff5f9; 
            --client-border: #fce4ec;
            --client-dark: #E85588;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #fff5f9; 
            overflow-x: hidden;
        }
        
        /* Sticky Professional Sidebar */
        .sidebar { 
            width: 260px; 
            position: sticky;
            top: 0;
            height: fit-content !important;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0,0,0,0.03);
            border-bottom-right-radius: 16px;
            z-index: 100;
        }
        
        /* Main Content Area */
        .main-content { 
            min-height: 100vh; 
            width: calc(100% - 260px);
            transition: all 0.3s ease;
        }
        
        /* Balanced Top Navbar (Matching Sidebar Header Height) */
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            width: 100%;
            height: 70px; /* Fits header height cleanly */
            border-bottom: 1px solid var(--client-border);
        }
        
        /* Card Styles */
        .card { 
            background: #fff; 
            border: 1px solid var(--client-border); 
            border-radius: 20px; 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255,107,157,0.08);
        }
        
        /* Mobile Screen Styles */
        @media (max-width: 768px) { 
            .sidebar { 
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                height: 100vh !important;
                margin-left: -260px; 
                border-radius: 0;
            } 
            .sidebar.show { 
                margin-left: 0; 
                box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            } 
            .main-content { 
                width: 100%;
            } 
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .main-content > .p-4 {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--client-border);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--client-pink);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-start">
        @include('components.sidebar-client')

        <div class="main-content flex-grow-1">
            <!-- Navbar without duplicate logo text -->
            <nav class="navbar px-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm me-2 d-md-none" id="sidebarToggle" style="background: rgba(255,107,157,0.1); color: #FF6B9D; border-radius: 8px;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <!-- Search Bar -->
                    <div class="d-none d-md-block">
                        <div class="input-group" style="width: 230px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Search salons..." style="border-radius: 50px 0 0 50px; border-color: var(--client-border); font-size: 0.8rem;">
                            <button class="btn btn-sm px-3" style="background: var(--client-pink); color: white; border-radius: 0 50px 50px 0; font-size: 0.8rem;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- My Waitlist Icon Button -->
                    <a href="{{ route('client.waitlist.index') }}" class="btn btn-sm position-relative d-flex align-items-center justify-content-center" style="background: rgba(255,107,157,0.1); border-radius: 50%; width: 35px; height: 35px;" title="My Waitlist">
                        <i class="fas fa-hourglass-half" style="color: var(--client-pink); font-size: 0.85rem;"></i>
                    </a>

                    <!-- Notifications Button -->
                    <div class="dropdown">
                        <button class="btn btn-sm position-relative d-flex align-items-center justify-content-center" id="notifBellBtn" style="background: rgba(255,107,157,0.1); border-radius: 50%; width: 35px; height: 35px;" data-bs-toggle="dropdown">
                            <i class="fas fa-bell" style="color: var(--client-pink); font-size: 0.85rem;"></i>
                            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                            <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.2em 0.4em; {{ $unreadCount > 0 ? '' : 'display:none;' }}">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 300px; max-height: 380px; overflow-y: auto;">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            @php $notifications = Auth::user()->notifications()->latest()->take(8)->get(); @endphp
                            @forelse($notifications as $notif)
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.notifications.index') }}" style="white-space: normal;">
                                        <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small><br>
                                        <span class="small {{ !$notif->read_at ? 'fw-semibold' : '' }}">{{ $notif->data['message'] ?? 'New Notification' }}</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @empty
                                <li><span class="dropdown-item text-muted text-center small">✨ No new notifications</span></li>
                            @endforelse
                            <li>
                                <a href="{{ route('client.notifications.index') }}" class="dropdown-item text-center small" style="color: var(--client-pink);">View all notifications</a>
                            </li>
                        </ul>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn dropdown-toggle d-flex align-items-center gap-2 p-0" style="background: none; border: none;" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 class="rounded-circle" 
                                 width="34" 
                                 height="34" 
                                 alt="{{ Auth::user()->name }}"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF6B9D&color=fff&size=100'"
                                 style="object-fit: cover; border: 2px solid var(--client-pink);">
                            <div class="text-start d-none d-md-block" style="line-height: 1.2;">
                                <div class="fw-semibold" style="font-size: 0.82rem;">{{ Auth::user()->name }}</div>
                                <div class="text-muted" style="font-size: 0.68rem;">Client</div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item small" href="{{ route('client.profile.index') }}">
                                <i class="fas fa-user-circle me-2"></i>My Profile
                            </a></li>
                            <li><a class="dropdown-item small" href="{{ route('client.appointments.index') }}">
                                <i class="fas fa-calendar-alt me-2"></i>My Appointments
                            </a></li>
                            <li><a class="dropdown-item small" href="{{ route('client.reviews.index') }}">
                                <i class="fas fa-star me-2" style="color:#f59e0b;"></i>My Reviews
                            </a></li>
                            <li><a class="dropdown-item small" href="{{ route('client.favorites.index') }}">
                                <i class="fas fa-heart me-2" style="color:#FF6B9D;"></i>Favorites
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item small text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="p-4">
                @include('partials.alerts')
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Auto-hide sidebar on click outside (mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('show')) {
                if (!sidebar.contains(event.target) && !toggleBtn?.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Confirm logout popup
        document.addEventListener('submit', function(e) {
            if (e.target && (e.target.id === 'logout-form' || e.target.id === 'sidebar-logout-form')) {
                e.preventDefault();
                const form = e.target;
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#FF6B9D',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        // Notification bell dropdown action
        document.getElementById('notifBellBtn')?.addEventListener('click', function() {
            const badge = document.getElementById('notifBadge');
            if (!badge || badge.style.display === 'none') return;

            fetch("{{ route('client.notifications.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(() => {
                badge.style.display = 'none';
            }).catch(() => {});
        });
    </script>
    
    @stack('scripts')
</body>
</html>