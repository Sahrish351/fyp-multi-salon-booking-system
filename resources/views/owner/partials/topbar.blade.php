@php
    $authUser = auth()->user();
    $unreadCount = method_exists($authUser, 'unreadNotifications')
        ? $authUser->unreadNotifications()->count()
        : 0;
    $initial = strtoupper(mb_substr($authUser->name ?? 'S', 0, 1));
@endphp

<style>
    /* ===== Topbar: upar, left aur right se juri hui ===== */
    .owner-topbar {
        display: flex;
        align-items: center;
        gap: 16px;
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-bottom: 1px solid #fbe3ee;
        padding: 12px 32px;
        margin: -28px -32px 26px;   /* main-content ke padding ko cancel karta hai */
        position: sticky;
        top: 0;
        z-index: 900;
        font-family: 'Poppins', sans-serif;
    }

    .topbar-hamburger {
        display: none;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 12px;
        background: #fff0f6;
        color: #5C2142;
        font-size: 22px;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ===== Search ===== */
    .topbar-search {
        position: relative;
        flex: 1;
        max-width: 400px;
        transition: max-width .3s ease;
    }
    .topbar-search:focus-within { max-width: 520px; }

    .topbar-search > i.search-ico {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #c46a95;
        font-size: 14px;
        transition: color .2s;
        pointer-events: none;
    }
    .topbar-search:focus-within > i.search-ico { color: #e8457f; }

    .topbar-search input {
        width: 100%;
        height: 44px;
        border: 1.5px solid #fbe3ee;
        background: #fff5f9;
        border-radius: 14px;
        padding: 0 60px 0 42px;
        font-size: 13.5px;
        font-family: 'Poppins', sans-serif;
        color: #5C2142;
        outline: none;
        transition: all .25s ease;
    }
    .topbar-search input::placeholder { color: #b98aa5; }
    .topbar-search input:focus {
        border-color: #f0568c;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(240, 86, 140, 0.14), 0 8px 22px rgba(232, 69, 127, 0.12);
    }

    .topbar-search .kbd-hint {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10.5px;
        font-weight: 600;
        color: #b98aa5;
        background: #fff;
        border: 1px solid #fbe3ee;
        border-radius: 6px;
        padding: 2px 7px;
        pointer-events: none;
        transition: opacity .2s;
    }
    .topbar-search:focus-within .kbd-hint { opacity: 0; }

    .topbar-search-results {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #fbe3ee;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(92, 33, 66, 0.18);
        padding: 8px;
        max-height: 380px;
        overflow-y: auto;
        z-index: 1000;
        animation: topbarDrop .18s ease;
    }
    .topbar-search-results.show { display: block; }
    @keyframes topbarDrop {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .topbar-search-label {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: #b98aa5;
        padding: 6px 12px 4px;
    }

    .topbar-search-results a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 9px 12px;
        border-radius: 10px;
        color: #5C2142;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: background .15s;
    }
    .topbar-search-results a .res-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #fff0f6;
        color: #e8457f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .topbar-search-results a:hover,
    .topbar-search-results a.active {
        background: #fff0f6;
    }
    .topbar-search-results a.active .res-ico,
    .topbar-search-results a:hover .res-ico {
        background: linear-gradient(135deg, #ff6fa5, #e8457f);
        color: #fff;
    }
    .topbar-search-empty {
        padding: 18px 12px;
        font-size: 13px;
        color: #a08a99;
        text-align: center;
    }

    /* ===== Right side ===== */
    .topbar-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
    }

    .topbar-icon-btn {
        position: relative;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #fff0f6;
        color: #e8457f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        text-decoration: none;
        transition: all .2s;
    }
    .topbar-icon-btn:hover { background: #ffe0ee; color: #d63384; }

    .topbar-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 9px;
        background: #e8457f;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .topbar-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #ff6fa5, #e8457f);
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(232, 69, 127, 0.35);
        transition: transform .2s;
    }
    .topbar-avatar:hover { transform: scale(1.06); }

    .topbar-dropdown {
        border: 1px solid #fbe3ee;
        border-radius: 14px;
        box-shadow: 0 14px 34px rgba(92, 33, 66, 0.16);
        padding: 8px;
        min-width: 230px;
        margin-top: 12px !important;
    }
    .topbar-dropdown-head {
        padding: 8px 12px;
        display: flex;
        flex-direction: column;
        color: #5C2142;
    }
    .topbar-dropdown-head small { color: #999; }
    .topbar-dropdown .dropdown-item {
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        padding: 9px 12px;
        color: #5C2142;
    }
    .topbar-dropdown .dropdown-item:hover { background: #fff0f6; }
    .topbar-dropdown .dropdown-item.text-danger { color: #dc3545; }

    /* ===== Tablet / Mobile ===== */
    @media (max-width: 991.98px) {
        .owner-topbar {
            padding: 10px 16px;
            margin: -20px -16px 20px;   /* mobile pe main-content ka padding 20px 16px hai */
        }
        .topbar-hamburger { display: flex; }
        .topbar-search:focus-within { max-width: none; }
    }
    @media (max-width: 575.98px) {
        .owner-topbar { gap: 10px; }
        .topbar-search { max-width: none; }
        .topbar-search .kbd-hint { display: none; }
    }
</style>

<header class="owner-topbar">

    {{-- Mobile hamburger (sidebar toggle) --}}
    <button class="topbar-hamburger" id="sidebarToggleBtn" type="button" aria-label="Open menu">
        <i class="bi bi-list"></i>
    </button>

    {{-- Search --}}
    <div class="topbar-search" id="topbarSearch">
        <i class="bi bi-search search-ico"></i>
        <input type="text" id="topbarSearchInput" placeholder="Search pages..." autocomplete="off">
        <span class="kbd-hint">Ctrl K</span>
        <div class="topbar-search-results" id="topbarSearchResults"></div>
    </div>

    <div class="topbar-actions">

        {{-- Notifications --}}
        <a href="{{ route('owner.notifications.index') }}" class="topbar-icon-btn" aria-label="Notifications">
            <i class="bi bi-bell"></i>
            @if($unreadCount > 0)
                <span class="topbar-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </a>

        {{-- Profile dropdown --}}
        <div class="dropdown">
            <button class="topbar-avatar" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $initial }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end topbar-dropdown">
                <li class="topbar-dropdown-head">
                    <strong>{{ $authUser->name }}</strong>
                    <small>{{ $authUser->email }}</small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('owner.profile') }}">
                        <i class="bi bi-shop me-2"></i> Salon Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('owner.settings.index') }}">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
(function () {
    const input   = document.getElementById('topbarSearchInput');
    const results = document.getElementById('topbarSearchResults');
    const box     = document.getElementById('topbarSearch');
    if (!input) return;

    // Sidebar ke links se pages ki list banao
    const pages = Array.from(document.querySelectorAll('#ownerSidebar .sidebar-nav a[href]'))
        .map(a => ({
            name: (a.querySelector('span')?.textContent || '').trim(),
            href: a.getAttribute('href'),
            icon: a.querySelector('.nav-ico')?.className || 'bi bi-dot'
        }))
        .filter(p => p.name && p.href && p.href !== '#' && p.name !== 'Logout' && p.name !== 'Visit Website');

    let activeIndex = -1;

    function render(list, label) {
        activeIndex = -1;
        if (!list.length) {
            results.innerHTML = '<div class="topbar-search-empty">No pages found</div>';
        } else {
            results.innerHTML =
                `<div class="topbar-search-label">${label}</div>` +
                list.map(p =>
                    `<a href="${p.href}">
                        <span class="res-ico"><i class="${p.icon}"></i></span>
                        <span>${p.name}</span>
                    </a>`
                ).join('');
        }
        results.classList.add('show');
    }

    function showAll() { render(pages, 'Quick links'); }

    function filterNow() {
        const q = input.value.trim().toLowerCase();
        if (!q) { showAll(); return; }
        render(pages.filter(p => p.name.toLowerCase().includes(q)), 'Results');
    }

    function setActive(items, index) {
        items.forEach(el => el.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }

    // Click / focus karte hi list khul jaye
    input.addEventListener('focus', filterNow);
    input.addEventListener('click', filterNow);
    input.addEventListener('input', filterNow);

    input.addEventListener('keydown', function (e) {
        const items = Array.from(results.querySelectorAll('a'));

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!items.length) return;
            activeIndex = (activeIndex + 1) % items.length;
            setActive(items, activeIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (!items.length) return;
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            setActive(items, activeIndex);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            const target = items[activeIndex] || items[0];
            if (target) window.location = target.href;
        } else if (e.key === 'Escape') {
            results.classList.remove('show');
            input.blur();
        }
    });

    // Bahar click karne pe band ho jaye
    document.addEventListener('click', function (e) {
        if (!box.contains(e.target)) results.classList.remove('show');
    });

    // Ctrl + K se search khulna
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            input.focus();
        }
    });
})();
</script>