
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - GlowAura Salon</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Custom Dashboard CSS (pinkish theme) --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @yield('extra-css')
</head>
<body>

    <div class="app-wrapper">

        {{-- Sidebar (alag file se include) --}}
        @include('owner.partials.sidebar')

        {{-- Main Content Area --}}
        <main class="main-content">

            {{-- Mobile top bar with hamburger toggle --}}
            <div class="mobile-topbar">
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
                    <i class="bi bi-list"></i>
                </button>
                <span style="font-weight:700; color:#5C2142;">GlowAura</span>
                <span></span>
            </div>

            @yield('content')

        </main>
    </div>

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Chart.js for graphs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    {{-- Sidebar toggle script (mobile) --}}
    <script>
        const sidebar = document.getElementById('ownerSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggleBtn = document.getElementById('sidebarToggleBtn');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.add('show');
                backdrop.classList.add('show');
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function () {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }
    </script>

    @yield('extra-js')
</body>
</html>
