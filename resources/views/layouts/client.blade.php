<!DOCTYPE html>
<html lang="en" data-theme="{{ Auth::user()->theme ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Account') — Glamora</title>

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --client-pink: #E91E8C;
            --client-light: #fff5f9;
            --client-border: #fce4ec;
            --client-dark: #c2185b;
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

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #fff5f9 100%);
            border-right: 1px solid var(--client-border);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            width: calc(100% - 260px);
            transition: all 0.3s ease;
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
            box-shadow: 0 8px 25px rgba(233,30,140,0.08);
        }

        /* Navbar Styles */
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px;
            }
            .sidebar.show {
                margin-left: 0;
                box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            }
            .main-content {
                margin-left: 0;
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
        ::-webkit-scrollbar-thumb:hover {
            background: var(--client-dark);
        }

        /* Notification Badge Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .badge-pulse {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('components.sidebar-client')

        <div class="main-content flex-grow-1">

            <!-- Navbar -->
            <nav class="navbar px-4 py-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm me-2 d-md-none" id="sidebarToggle" style="background: rgba(233,30,140,0.1); color: #E91E8C; border-radius: 10px;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="navbar-brand fw-bold" href="{{ route('client.dashboard') }}" style="font-family: 'Playfair Display', serif;">
                        <span style="color:#E91E8C;">Glam</span><span style="color:#C9A96E;">ora</span>
                    </a>
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">

                    <!-- My Waitlist -->
                    <a href="{{ route('client.waitlist.index') }}" class="btn btn-sm position-relative d-flex align-items-center justify-content-center" style="background: rgba(233,30,140,0.1); border-radius: 50%; width: 38px; height: 38px;" title="My Waitlist">
                        <i class="fas fa-hourglass-half" style="color: var(--client-pink);"></i>
                    </a>

                    <!-- ============================================================ -->
                    <!-- ✅ NOTIFICATION BELL - COMPLETE WORKING                   -->
                    <!-- ============================================================ -->
                    <div class="dropdown">
                        <button class="btn btn-sm position-relative" style="background: rgba(233,30,140,0.1); border-radius: 50%; width: 38px; height: 38px;" data-bs-toggle="dropdown">
                            <i class="fas fa-bell" style="color: var(--client-pink);"></i>
                            @php
                                $unreadCount = auth()->user()->unreadNotifications()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-pulse" style="font-size: 0.65rem; min-width: 20px;">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 340px; max-height: 420px; overflow-y: auto; border-radius: 16px; border: 1px solid #fce4ec; padding: 0;">

                            <!-- Header -->
                            <li class="dropdown-header d-flex justify-content-between align-items-center px-4 py-3" style="background: #fcf9fc; border-bottom: 1px solid #fce4ec; border-radius: 16px 16px 0 0;">
                                <span class="fw-bold" style="color: #2d1f2c;">
                                    <i class="fas fa-bell me-2" style="color: #E91E8C;"></i> Notifications
                                </span>
                                @if($unreadCount > 0)
                                    <form action="{{ route('client.notifications.mark-all-read') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm text-decoration-none" style="color: #E91E8C; font-weight: 600; background: none; border: none;">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </li>

                            <!-- Notifications List -->
                            @php
                                $notifications = auth()->user()->notifications()->take(8)->get();
                            @endphp

                            @forelse($notifications as $notif)
                                <li class="dropdown-item py-3 px-4 border-bottom" style="border-color: #f5eaf0; background: {{ $notif->read_at ? '#fff' : '#fcf4f8' }};">
                                    <div class="d-flex align-items-start gap-3">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $notif->read_at ? '#f5f5f5' : '#fce4ec' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <span style="font-size: 1.1rem;">
                                                {{ $notif->data['status'] === 'confirmed' ? '✅' : ($notif->data['status'] === 'cancelled' ? '❌' : '📋') }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold" style="color: #2d1f2c; font-size: 0.85rem;">
                                                {{ $notif->data['status_text'] ?? 'Update' }}
                                            </div>
                                            <div style="color: #666; font-size: 0.75rem;">
                                                {{ $notif->data['message'] ?? 'Your appointment has been updated.' }}
                                            </div>
                                            <div style="color: #aaa; font-size: 0.65rem; margin-top: 2px;">
                                                {{ $notif->data['service_name'] ?? '' }} · {{ $notif->data['salon_name'] ?? '' }}
                                            </div>
                                            <div style="color: #ccc; font-size: 0.6rem; margin-top: 2px;">
                                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                        @if(!$notif->read_at)
                                            <span class="badge rounded-pill" style="background: #E91E8C; font-size: 0.5rem; padding: 3px 8px; flex-shrink: 0;">New</span>
                                        @endif
                                    </div>
                                    @if(!$notif->read_at)
                                        <form action="{{ route('client.notifications.mark-read', $notif->id) }}" method="POST" class="float-end mt-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm text-decoration-none" style="color: #E91E8C; font-size: 0.6rem; background: none; border: none;">
                                                Mark as read
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @empty
                                <li class="dropdown-item py-4 text-center" style="color: #aaa;">
                                    <i class="fas fa-bell-slash" style="font-size: 1.5rem; display: block; margin-bottom: 8px; color: #e0d5dd;"></i>
                                    No notifications yet
                                </li>
                            @endforelse

                            <!-- Footer -->
                            <li class="dropdown-footer text-center py-2" style="background: #fcf9fc; border-top: 1px solid #fce4ec; border-radius: 0 0 16px 16px;">
                                <a href="{{ route('client.notifications.index') }}" class="text-decoration-none small" style="color: #E91E8C; font-weight: 600;">
                                    View all notifications
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- ============================================================ -->
                    <!-- ✅ END NOTIFICATION BELL                                    -->
                    <!-- ============================================================ -->

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn dropdown-toggle d-flex align-items-center gap-2" style="background: none; border: none;" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=E91E8C&color=fff' }}"
                                 class="rounded-circle"
                                 width="36"
                                 height="36"
                                 alt="avatar"
                                 style="object-fit: cover; border: 2px solid var(--client-pink);">
                            <div class="text-start d-none d-md-block">
                                <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                                <div class="text-muted small">Client</div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/client/profile"><i class="fas fa-user-circle me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="/client/appointments"><i class="fas fa-calendar-alt me-2"></i>My Appointments</a></li>
                            <li><a class="dropdown-item" href="/client/reviews"><i class="fas fa-star me-2" style="color:#f59e0b;"></i>My Reviews</a></li>
                            <li><a class="dropdown-item" href="/client/favorites"><i class="fas fa-heart me-2" style="color:#E91E8C;"></i>Favorites</a></li>
                            <li><a class="dropdown-item" href="/client/settings"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
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
        // Sidebar Toggle
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

        // Confirm logout
        document.getElementById('logout-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#E91E8C',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 500);
                }, 4000);
            });
        }, 1000);
    </script>

    @stack('scripts')
</body>
</html>