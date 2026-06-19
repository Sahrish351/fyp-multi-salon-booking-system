
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $salon->name }} — Glamora</title>
    <meta name="description" content="{{ $salon->description ?? 'Book appointments at '.$salon->name.' on Glamora.' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; color: #1a1a1a; background: #fff; -webkit-font-smoothing: antialiased; }
    a { text-decoration: none; color: inherit; }
 
    .g-nav {
        background: #fff;
        border-bottom: 1px solid #ebebeb;
        padding: 0 32px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .g-nav .brand {
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -1px;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-style: italic;
    }
    .nav-search-pill {
        display: flex;
        align-items: center;
        background: #f5f5f5;
        border-radius: 50px;
        padding: 6px 6px 6px 18px;
        gap: 0;
        flex: 1;
        max-width: 560px;
        margin: 0 24px;
        border: 1px solid #e8e8e8;
        transition: box-shadow .2s;
    }
    .nav-search-pill:focus-within { box-shadow: 0 0 0 2px #1a1a1a; background: #fff; }
    .nav-search-pill input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.85rem;
        color: #1a1a1a;
        flex: 1;
        min-width: 0;
    }
    .nav-search-pill input::placeholder { color: #999; }
    .nav-search-pill .ns-divider { width: 1px; height: 20px; background: #d8d8d8; margin: 0 12px; flex-shrink: 0; }
    .nav-search-pill .ns-icon { color: #999; font-size: 0.82rem; margin-right: 6px; flex-shrink: 0; }
    .btn-ns-search {
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 50px;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        flex-shrink: 0;
        transition: background .15s;
    }
    .btn-ns-search:hover { background: #E91E8C; }
    .g-nav .nav-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .btn-nav-menu {
        background: #fff;
        border: 1.5px solid #e0e0e0;
        color: #1a1a1a;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-nav-menu:hover { border-color: #1a1a1a; }
    .btn-nav-ghost {
        background: transparent;
        border: none;
        color: #1a1a1a;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-nav-ghost:hover { background: #f5f5f5; }
    .btn-nav-outline {
        background: #fff;
        border: 1.5px solid #1a1a1a;
        color: #1a1a1a;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 50px;
        cursor: pointer;
        transition: all .15s;
        display: inline-block;
    }
    .btn-nav-outline:hover { background: #1a1a1a; color: #fff; }
 
    .breadcrumb-bar {
        padding: 14px 32px;
        font-size: 0.82rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 6px;
        border-bottom: 1px solid #f5f5f5;
    }
    .breadcrumb-bar a { color: #888; transition: color .15s; }
    .breadcrumb-bar a:hover { color: #1a1a1a; }
    .breadcrumb-bar .sep { color: #ccc; }
    .breadcrumb-bar .current { color: #1a1a1a; font-weight: 500; }
 
    .detail-wrap { max-width: 1280px; margin: 0 auto; padding: 0 32px; }
    @media(max-width:576px) { .detail-wrap { padding: 0 16px; } }
 
    .salon-header { padding: 24px 0 16px; }
    .salon-title {
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 800;
        color: #1a1a1a;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .salon-title .verified-badge {
        background: #7c3aed;
        color: #fff;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .salon-meta-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        font-size: 0.88rem;
        color: #555;
        margin-bottom: 0;
    }
    .salon-meta-row .rating-num { font-weight: 800; color: #1a1a1a; }
    .salon-meta-row .stars { color: #ffc107; letter-spacing: 1px; font-size: 0.9rem; }
    .salon-meta-row .review-count { color: #E91E8C; font-weight: 500; cursor: pointer; }
    .salon-meta-row .review-count:hover { text-decoration: underline; }
    .salon-meta-row .dot { color: #ccc; }
    .salon-meta-row .status-open { color: #22c55e; font-weight: 600; }
    .salon-meta-row .status-closed { color: #E91E8C; font-weight: 600; }
    .salon-meta-row .address { color: #555; }
    .salon-meta-row .directions { color: #E91E8C; font-weight: 600; cursor: pointer; }
    .salon-meta-row .directions:hover { text-decoration: underline; }
    .action-btns { display: flex; align-items: center; gap: 8px; }
    .btn-icon-round {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1.5px solid #e0e0e0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.9rem;
        color: #555;
        transition: all .15s;
    }
    .btn-icon-round:hover { border-color: #1a1a1a; color: #1a1a1a; }
    .btn-icon-round.liked i { color: #E91E8C; }
 
    .photo-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 8px;
        height: 460px;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 0;
        position: relative;
    }
    @media(max-width:768px) { .photo-grid { grid-template-columns: 1fr; grid-template-rows: 280px; height: auto; } }
    .photo-grid .main-photo { grid-row: 1 / 3; overflow: hidden; position: relative; cursor: pointer; }
    .photo-grid .main-photo img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
    .photo-grid .main-photo:hover img { transform: scale(1.03); }
    .photo-grid .side-photo { overflow: hidden; position: relative; cursor: pointer; }
    .photo-grid .side-photo img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
    .photo-grid .side-photo:hover img { transform: scale(1.04); }
    .photo-grid .side-photo.last { position: relative; }
    .see-all-photos {
        position: absolute;
        bottom: 14px;
        right: 14px;
        background: rgba(255,255,255,0.95);
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        padding: 7px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #1a1a1a;
        cursor: pointer;
        backdrop-filter: blur(6px);
        transition: all .15s;
        z-index: 2;
    }
    .see-all-photos:hover { background: #fff; border-color: #1a1a1a; }
 
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 48px;
        padding: 32px 0 60px;
        align-items: start;
    }
    @media(max-width:992px) { .content-grid { grid-template-columns: 1fr; } }
 
    /* ✅ Tabs are now pure scroll-navigation, not show/hide controllers */
    .salon-tabs {
        display: flex;
        align-items: center;
        gap: 0;
        border-bottom: 1px solid #e8e8e8;
        margin-bottom: 32px;
        position: sticky;
        top: 64px;
        background: #fff;
        z-index: 100;
        padding: 0;
        overflow-x: auto;
    }
    .salon-tab {
        padding: 14px 20px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #888;
        cursor: pointer;
        border-bottom: 2.5px solid transparent;
        transition: all .15s;
        border-top: none;
        border-left: none;
        border-right: none;
        background: none;
        margin-bottom: -1px;
        white-space: nowrap;
    }
    .salon-tab:hover { color: #1a1a1a; }
    .salon-tab.active { color: #1a1a1a; border-bottom-color: #1a1a1a; }
 
    /* ✅ Each section now has spacing + a scroll-margin so the sticky
         tab bar doesn't cover the heading when we scroll to it */
    .page-section { margin-bottom: 56px; scroll-margin-top: 130px; }
    .page-section:last-child { margin-bottom: 0; }
    .page-section h3 { font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin-bottom: 16px; }
 
    .services-cat-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
    .svc-cat-btn {
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        padding: 7px 16px;
        font-size: 0.83rem;
        font-weight: 600;
        color: #555;
        background: #fff;
        cursor: pointer;
        transition: all .15s;
    }
    .svc-cat-btn.active, .svc-cat-btn:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
 
    .service-list { display: flex; flex-direction: column; gap: 0; }
    .service-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        margin-bottom: 8px;
        transition: all .15s;
        cursor: pointer;
    }
    .service-row:hover { border-color: #1a1a1a; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .service-row .sr-left .sr-name { font-size: 0.92rem; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; }
    .service-row .sr-left .sr-duration { font-size: 0.8rem; color: #888; margin-bottom: 6px; }
    .service-row .sr-left .sr-price { font-size: 1rem; font-weight: 700; color: #1a1a1a; }
    .btn-book-service {
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.83rem;
        font-weight: 600;
        color: #1a1a1a;
        background: #fff;
        cursor: pointer;
        transition: all .15s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .btn-book-service:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
 
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
    }
    .team-member { text-align: center; cursor: pointer; }
    .team-member .tm-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        margin: 0 auto 10px;
        overflow: hidden;
        background: #e8e4f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 700;
        color: #7c3aed;
    }
    .team-member .tm-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .team-member .tm-rating { font-size: 0.78rem; font-weight: 600; color: #1a1a1a; display: flex; align-items: center; justify-content: center; gap: 3px; margin-bottom: 4px; }
    .team-member .tm-rating i { color: #ffc107; font-size: 0.72rem; }
    .team-member .tm-name { font-size: 0.88rem; font-weight: 700; color: #1a1a1a; margin-bottom: 2px; }
    .team-member .tm-role { font-size: 0.78rem; color: #888; }
 
    .review-item { border: 1px solid #f0f0f0; border-radius: 12px; padding: 16px; margin-bottom: 16px; }
    .review-item .ri-header { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .review-item .ri-av {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #e8e4f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #7c3aed;
        flex-shrink: 0;
    }
    .review-item .ri-name { font-weight: 700; font-size: 0.88rem; color: #1a1a1a; }
    .review-item .ri-date { font-size: 0.75rem; color: #aaa; }
    .review-item .ri-stars { color: #ffc107; font-size: 0.85rem; margin-bottom: 8px; }
    .review-item .ri-text { font-size: 0.85rem; color: #555; line-height: 1.6; }
    .big-rating { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .big-rating .br-num { font-size: 3rem; font-weight: 900; color: #1a1a1a; line-height: 1; }
    .big-rating .br-stars { color: #ffc107; font-size: 1.5rem; }
    .big-rating .br-count { font-size: 0.85rem; color: #888; }
 
    .about-section p { font-size: 0.9rem; color: #555; line-height: 1.8; margin-bottom: 24px; }
    .map-placeholder {
        width: 100%;
        height: 280px;
        background: #f0f0f0;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        margin-bottom: 12px;
    }
    .map-placeholder iframe { width: 100%; height: 100%; border: none; }
    .map-placeholder .map-fallback {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #e8e4f5;
        gap: 8px;
        cursor: pointer;
    }
    .address-line { font-size: 0.88rem; color: #555; margin-bottom: 24px; }
    .address-line a { color: #E91E8C; font-weight: 600; }
    .hours-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 24px; }
    @media(max-width:576px) { .hours-grid { grid-template-columns: 1fr; } }
    .hours-table h4 { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 14px; }
    .hour-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f5; font-size: 0.85rem; }
    .hour-row .day { color: #555; display: flex; align-items: center; gap: 8px; }
    .hour-row .day .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .hour-row .day .dot.open { background: #22c55e; }
    .hour-row .day .dot.closed { background: #ccc; }
    .hour-row .time { color: #1a1a1a; font-weight: 500; }
    .hour-row .time.closed { color: #888; }
    .hour-row.today .day { font-weight: 700; color: #1a1a1a; }
    .hour-row.today .time { font-weight: 700; }
    .add-info h4 { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 14px; }
    .add-info-item { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: #555; margin-bottom: 10px; }
    .add-info-item i { color: #7c3aed; width: 16px; }
 
    .nearby-section { margin-top: 8px; }
    .nearby-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media(max-width:768px) { .nearby-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width:480px) { .nearby-grid { grid-template-columns: 1fr; } }
    .nearby-card { cursor: pointer; border-radius: 14px; overflow: hidden; border: 1px solid #f0f0f0; transition: all .2s; }
    .nearby-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .nearby-card .nc-img { height: 130px; overflow: hidden; position: relative; }
    .nearby-card .nc-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
    .nearby-card:hover .nc-img img { transform: scale(1.05); }
    .nearby-card .nc-fav { position: absolute; top: 8px; right: 8px; width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .nearby-card .nc-fav i { color: #ccc; font-size: 0.78rem; }
    .nearby-card .nc-body { padding: 10px; }
    .nearby-card .nc-name { font-size: 0.85rem; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; }
    .nearby-card .nc-meta { font-size: 0.75rem; color: #888; }
 
    .other-biz-section h3 { font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin-bottom: 16px; }
    .other-biz-btn {
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 24px;
        transition: background .15s;
        display: inline-block;
    }
    .other-biz-btn:hover { background: #E91E8C; }
    .other-biz-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 6px 40px; }
    @media(max-width:992px) { .other-biz-grid { grid-template-columns: repeat(3,1fr); } }
    @media(max-width:576px) { .other-biz-grid { grid-template-columns: repeat(2,1fr); } }
    .other-biz-grid a { font-size: 0.82rem; color: #555; display: block; padding: 4px 0; transition: color .15s; }
    .other-biz-grid a:hover { color: #E91E8C; }
 
    .booking-sidebar {
        background: #fff;
        border: 1.5px solid #e8e8e8;
        border-radius: 20px;
        padding: 24px;
        position: sticky;
        top: 80px;
    }
    .booking-sidebar .bs-name { font-size: 1.4rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.3px; margin-bottom: 12px; line-height: 1.2; }
    .booking-sidebar .bs-rating { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
    .booking-sidebar .bs-rating .num { font-size: 0.95rem; font-weight: 800; color: #1a1a1a; }
    .booking-sidebar .bs-rating .stars { color: #ffc107; font-size: 0.9rem; letter-spacing: 1px; }
    .booking-sidebar .bs-rating .count { color: #E91E8C; font-size: 0.85rem; font-weight: 500; cursor: pointer; }
    .booking-sidebar .bs-rating .count:hover { text-decoration: underline; }
    .bs-featured {
        background: #f5f5f5;
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #555;
        display: inline-block;
        margin-bottom: 20px;
    }
    .btn-book-now {
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 15px;
        font-size: 1rem;
        font-weight: 700;
        width: 100%;
        cursor: pointer;
        transition: all .2s;
        margin-bottom: 22px;
        display: block;
        text-align: center;
    }
    .btn-book-now:hover { background: #E91E8C; transform: translateY(-1px); }
 
    /* ✅ FIX: Open/Closed status row — was overlapping the Book now
       button because the dash + hours text could wrap onto a second
       line with no extra spacing. Now uses flex-wrap with a proper
       line-height and bottom margin so it never visually touches the
       button above it, however many lines it wraps to. */
    .bs-info-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        cursor: pointer;
    }
    .bs-info-row i { font-size: 0.9rem; margin-top: 2px; flex-shrink: 0; }
    .bs-info-row .bs-info-text {
        font-size: 0.85rem;
        color: #555;
        line-height: 1.6;
        flex-wrap: wrap;
    }
    .bs-info-row .bs-info-text .status-open { color: #22c55e; font-weight: 600; }
    .bs-info-row .bs-info-text .status-closed { color: #E91E8C; font-weight: 600; }
    .bs-info-row .bs-info-text .bs-toggle { color: #888; cursor: pointer; font-size: 0.78rem; margin-left: 4px; }
    .bs-info-row .directions-link { color: #E91E8C; font-weight: 600; font-size: 0.82rem; }
    .bs-info-row .directions-link:hover { text-decoration: underline; }
    .bs-hours-expand { background: #f9f9f9; border-radius: 10px; padding: 12px; margin-bottom: 16px; display: none; }
    .bs-hours-expand.show { display: block; }
    .bs-hours-row { display: flex; justify-content: space-between; font-size: 0.8rem; padding: 4px 0; color: #555; }
    .bs-hours-row.today { font-weight: 700; color: #1a1a1a; }
 
    .photo-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 2000; align-items: center; justify-content: center; }
    .photo-modal.show { display: flex; }
    .photo-modal-inner { max-width: 90vw; max-height: 90vh; position: relative; }
    .photo-modal-inner img { max-width: 100%; max-height: 85vh; object-fit: contain; border-radius: 12px; }
    .photo-modal-close { position: absolute; top: -40px; right: 0; color: #fff; font-size: 1.4rem; cursor: pointer; background: none; border: none; }
    </style>
</head>
<body>
 
<nav class="g-nav">
    <a href="{{ route('home') }}" class="brand" style="flex-shrink:0;">glamora</a>
 
    <form action="{{ route('salons.index') }}" method="GET" class="nav-search-pill d-none d-md-flex">
        <i class="fas fa-search ns-icon" style="color:#999;"></i>
        <input type="text" name="search" placeholder="All treatments">
        <div class="ns-divider"></div>
        <i class="fas fa-map-marker-alt ns-icon" style="color:#E91E8C;"></i>
        <input type="text" name="city" placeholder="Current location">
        <div class="ns-divider"></div>
        <i class="fas fa-calendar ns-icon"></i>
        <input type="text" placeholder="Any time">
        <button type="submit" class="btn-ns-search"><i class="fas fa-search"></i></button>
    </form>
 
    <div class="nav-right">
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-nav-ghost d-none d-md-block">Dashboard</a>
            @elseif(Auth::user()->isOwner())
                <a href="{{ route('owner.dashboard') }}" class="btn-nav-ghost d-none d-md-block">My Salons</a>
            @else
                <a href="{{ route('client.dashboard') }}" class="btn-nav-ghost d-none d-md-block">My Account</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-nav-ghost d-none d-md-block">Log in</a>
            <a href="{{ route('register.owner') }}" class="btn-nav-outline d-none d-md-block">List your business</a>
        @endauth
        <button class="btn-nav-menu" onclick="toggleMobileMenu()">
            Menu <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>
 
<div id="mobileMenu" style="display:none;position:fixed;top:64px;left:0;right:0;background:#fff;z-index:999;border-bottom:1px solid #f0f0f0;box-shadow:0 8px 30px rgba(0,0,0,0.1);">
    <div style="padding:1rem;">
        @foreach([['Home','home'],['Find a salon','salons.index'],['Services','services.index'],['About','about'],['Contact','contact'],['List your business','register.owner']] as [$label,$route])
        <a href="{{ route($route) }}" style="display:block;padding:12px 0;font-size:0.92rem;font-weight:600;color:#1a1a1a;border-bottom:1px solid #f5f5f5;">{{ $label }}</a>
        @endforeach
    </div>
</div>
 
<div class="breadcrumb-bar">
    <a href="{{ route('home') }}">Home</a>
    <span class="sep">·</span>
    <a href="{{ route('salons.index') }}">Salons</a>
    <span class="sep">·</span>
    <a href="{{ route('salons.index', ['city' => $salon->city]) }}">{{ $salon->city }}</a>
    <span class="sep">·</span>
    <span class="current">{{ $salon->name }}</span>
</div>
 
<div class="detail-wrap">
 
    {{-- SALON HEADER --}}
    <div class="salon-header">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="salon-title">
                    {{ $salon->name }}
                    <span class="verified-badge" title="Verified by Glamora">
                        <i class="fas fa-check" style="font-size:0.65rem;"></i>
                    </span>
                </h1>
                <div class="salon-meta-row">
                    <span class="rating-num">{{ number_format($salon->rating, 1) }}</span>
                    <span class="stars">
                        @for($i=1;$i<=5;$i++)
                            {{ $i <= round($salon->rating) ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span class="review-count" onclick="scrollToSection('reviews')">({{ $salon->total_reviews * 10 + 250 }})</span>
                    <span class="dot">·</span>
                    @php
                        $now = now();
                        $dayName = strtolower($now->format('l'));
                        $openTime = $salon->open_time ? \Carbon\Carbon::parse($salon->open_time) : null;
                        $closeTime = $salon->close_time ? \Carbon\Carbon::parse($salon->close_time) : null;
                        $isOpen = $openTime && $closeTime && $now->format('H:i') >= $openTime->format('H:i') && $now->format('H:i') <= $closeTime->format('H:i');
 
                        // ✅ Build a precise Google Maps URL. If lat/lng exist on the
                        // salon, use them directly (most accurate — pinpoints the
                        // exact spot). Otherwise fall back to a clean text search
                        // query, avoiding duplicate city names in the string.
                        $hasCoords = !empty($salon->latitude) && !empty($salon->longitude);
                        if ($hasCoords) {
                            $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$salon->latitude},{$salon->longitude}";
                        } else {
                            $addressForMap = trim($salon->address);
                            // avoid "Lahore, Lahore" style duplication if address already ends with the city name
                            if (!str_ends_with(strtolower($addressForMap), strtolower($salon->city))) {
                                $addressForMap .= ', ' . $salon->city;
                            }
                            $addressForMap .= ', Pakistan';
                            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($addressForMap);
                        }
                    @endphp
                    @if($isOpen)
                        <span class="status-open">Open</span>
                    @else
                        <span class="status-closed">Closed</span>
                        @if($openTime)
                        <span>– opens at {{ $openTime->format('g:i A') }}</span>
                        @endif
                    @endif
                    <span class="dot">·</span>
                    <span class="address">{{ $salon->address }}, {{ $salon->city }}</span>
                    <a class="directions" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
                        Get directions
                    </a>
                </div>
            </div>
            <div class="action-btns">
                <button class="btn-icon-round" title="Share">
                    <i class="fas fa-share-alt"></i>
                </button>
                @auth
                <form action="{{ route('client.favorites.toggle', $salon->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-icon-round {{ Auth::user()->favoriteSalons()->where('salon_id',$salon->id)->exists() ? 'liked' : '' }}" title="Save">
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
                @else
                <button class="btn-icon-round" onclick="window.location='{{ route('login') }}'" title="Save">
                    <i class="fas fa-heart"></i>
                </button>
                @endauth
            </div>
        </div>
    </div>
 
    {{-- PHOTO GRID --}}
    <div class="photo-grid" id="photoGrid">
        <div class="main-photo" onclick="openPhoto('{{ $salon->cover_url }}')">
            <img src="{{ $salon->cover_url }}" alt="{{ $salon->name }}"
                 onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80'">
        </div>
        @php $galleryImages = $salon->gallery->take(2); @endphp
        <div class="side-photo" onclick="openPhoto('{{ $galleryImages->first()?->image_url ?? $salon->logo_url }}')">
            <img src="{{ $galleryImages->first()?->image_url ?? 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400&q=70' }}"
                 alt="{{ $salon->name }}"
                 onerror="this.src='https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400&q=70'">
        </div>
        <div class="side-photo last" onclick="openPhoto('{{ $galleryImages->skip(1)->first()?->image_url ?? $salon->logo_url }}')">
            <img src="{{ $galleryImages->skip(1)->first()?->image_url ?? 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400&q=70' }}"
                 alt="{{ $salon->name }}"
                 onerror="this.src='https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400&q=70'">
            <a href="{{ route('salons.gallery', $salon->slug) }}" class="see-all-photos" style="text-decoration:none; color:#1a1a1a;" onclick="event.stopPropagation()">
                See all images
            </a>
        </div>
    </div>
 
    {{-- CONTENT GRID --}}
    <div class="content-grid">
 
        {{-- LEFT: Tabs (scroll-nav only) + all sections stacked --}}
        <div>
            {{-- ✅ TABS now just scroll to the matching section.
                 Nothing is hidden — every section stays on the page. --}}
            <div class="salon-tabs" id="salonTabs">
                <button class="salon-tab active" data-section="services" onclick="scrollToSection('services')">Services</button>
                <button class="salon-tab" data-section="team" onclick="scrollToSection('team')">Team</button>
                <button class="salon-tab" data-section="reviews" onclick="scrollToSection('reviews')">Reviews</button>
                <button class="salon-tab" data-section="about" onclick="scrollToSection('about')">About</button>
                <button class="salon-tab" data-section="features" onclick="scrollToSection('features')">Features</button>
            </div>
 
            {{-- ===== SERVICES SECTION ===== --}}
            <div id="section-services" class="page-section">
                <h3>Services</h3>
 
                @php $serviceCategories = $salon->services->groupBy('category.name'); @endphp
                <div class="services-cat-tabs">
                    <button class="svc-cat-btn active" onclick="filterServiceCat('all',this)">All</button>
                    @foreach($serviceCategories as $catName => $services)
                    <button class="svc-cat-btn" onclick="filterServiceCat('{{ Str::slug($catName) }}',this)">
                        {{ $catName }}
                    </button>
                    @endforeach
                </div>
 
                <div class="service-list">
                    @forelse($salon->services->where('is_active',true) as $service)
                    <div class="service-row" data-cat="{{ Str::slug($service->category->name ?? 'general') }}">
                        <div class="sr-left">
                            <div class="sr-name">{{ $service->name }}</div>
                            <div class="sr-duration">{{ $service->duration ?? 60 }} min</div>
                            <div class="sr-price">Rs. {{ number_format($service->price) }}</div>
                        </div>
                        <a href="{{ route('booking.step1', $salon->id) }}?service={{ $service->id }}" class="btn-book-service">Book</a>
                    </div>
                    @empty
                    <div style="color:#888;font-size:0.88rem;padding:20px 0;">No services listed yet.</div>
                    @endforelse
                </div>
            </div>
 
            {{-- ===== TEAM SECTION ===== --}}
            <div id="section-team" class="page-section">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 style="margin-bottom:0;">Team</h3>
                </div>
                <div class="team-grid">
                    @forelse($salon->stylists->where('is_active',true) as $stylist)
                    <div class="team-member">
                        <div class="tm-avatar">
                            @if($stylist->avatar)
                                <img src="{{ $stylist->avatar_url }}" alt="{{ $stylist->name }}"
                                     onerror="this.parentElement.innerHTML='{{ substr($stylist->name,0,1) }}'">
                            @else
                                {{ substr($stylist->name,0,1) }}
                            @endif
                        </div>
                        <div class="tm-rating">
                            <i class="fas fa-star"></i>
                            {{ number_format($stylist->rating ?: 5.0, 1) }}
                        </div>
                        <div class="tm-name">{{ $stylist->name }}</div>
                        <div class="tm-role">{{ $stylist->specializations ? Str::limit($stylist->specializations,20) : 'Stylist' }}</div>
                    </div>
                    @empty
                    <div style="color:#888;font-size:0.88rem;">No team members listed.</div>
                    @endforelse
                </div>
            </div>
 
            {{-- ===== REVIEWS SECTION ===== --}}
            <div id="section-reviews" class="page-section">
                <h3>Reviews</h3>
                <div class="big-rating">
                    <div class="br-num">{{ number_format($salon->rating, 1) }}</div>
                    <div>
                        <div class="br-stars">
                            @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star" style="color:{{ $i <= round($salon->rating) ? '#ffc107' : '#e5e7eb' }};"></i>
                            @endfor
                        </div>
                        <div class="br-count">{{ $salon->total_reviews * 10 + 250 }} reviews</div>
                    </div>
                </div>
 
                @forelse($salon->reviews->where('is_approved',true)->take(6) as $review)
                <div class="review-item">
                    <div class="ri-header">
                        <div class="ri-av">{{ substr($review->client->name,0,1) }}</div>
                        <div>
                            <div class="ri-name">{{ $review->client->name }}</div>
                            <div class="ri-date">{{ $review->created_at->format('D, d M Y') }} at {{ $review->created_at->format('h:i A') }}</div>
                        </div>
                    </div>
                    <div class="ri-stars">
                        @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star" style="color:{{ $i<=$review->rating ? '#ffc107' : '#e5e7eb' }};font-size:0.9rem;"></i>
                        @endfor
                    </div>
                    @if($review->comment)
                    <div class="ri-text">{{ $review->comment }}</div>
                    @endif
                    @if($review->reply)
                    <div style="background:#f5f5f5;border-radius:10px;padding:12px;margin-top:12px;">
                        <div style="font-size:0.78rem;font-weight:700;color:#555;margin-bottom:4px;">Owner's reply</div>
                        <div style="font-size:0.83rem;color:#555;">{{ $review->reply->reply }}</div>
                    </div>
                    @endif
                </div>
                @empty
                <div style="text-align:center;padding:40px 0;color:#888;">
                    <i class="fas fa-star fa-3x" style="color:#f0f0f0;margin-bottom:12px;display:block;"></i>
                    No reviews yet. Be the first to review!
                </div>
                @endforelse
            </div>
 
            {{-- ===== ABOUT SECTION ===== --}}
            <div id="section-about" class="page-section">
                <div class="about-section">
                    <h3>About</h3>
                    <p>{{ $salon->description ?? 'Welcome to '.$salon->name.'. We are dedicated to providing top-quality beauty services in '.$salon->city.'. Our experienced team of stylists and beauty experts are here to give you the best experience possible. Book your appointment today!' }}</p>
 
                    <div class="map-placeholder">
    @if(config('services.google_maps.key'))
        <iframe
            width="100%"
            height="100%"
            style="border:0;border-radius:16px;"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ urlencode($salon->address . ', ' . $salon->city . ', Pakistan') }}&zoom=15">
        </iframe>
    @else
        <div class="map-fallback" onclick="window.open('{{ $mapsUrl }}','_blank')">
            <i class="fas fa-map-marker-alt" style="font-size:2rem;color:#7c3aed;"></i>
            <div style="font-size:0.85rem;color:#555;font-weight:600;">{{ $salon->name }}</div>
            <div style="font-size:0.78rem;color:#888;">{{ $salon->address }}, {{ $salon->city }}</div>
        </div>
    @endif
</div>
                    <p class="address-line">
                        {{ $salon->address }}, {{ $salon->city }}, Pakistan
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="directions-link"> Get directions</a>
                    </p>
 
                    <div class="hours-grid">
                        <div class="hours-table">
                            <h4>Opening times</h4>
                            @php
                                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                $todayName = now()->format('l');
                                $workingDays = is_array($salon->working_days) ? $salon->working_days : $days;
                            @endphp
                            @foreach($days as $day)
                            @php $isWorkday = in_array($day, $workingDays) || in_array(strtolower($day), array_map('strtolower', $workingDays)); @endphp
                            <div class="hour-row {{ $day === $todayName ? 'today' : '' }}">
                                <div class="day">
                                    <span class="dot {{ $isWorkday ? 'open' : 'closed' }}"></span>
                                    {{ $day }}
                                </div>
                                @if($isWorkday)
                                <div class="time">
                                    {{ $salon->open_time ? \Carbon\Carbon::parse($salon->open_time)->format('g:i A') : '10:00 AM' }}
                                    – {{ $salon->close_time ? \Carbon\Carbon::parse($salon->close_time)->format('g:i A') : '8:00 PM' }}
                                </div>
                                @else
                                <div class="time closed">Closed</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
 
                        <div class="add-info">
                            <h4>Additional information</h4>
                            <div class="add-info-item">
                                <i class="fas fa-check-circle"></i>
                                Verified business by Glamora
                            </div>
                            <div class="add-info-item">
                                <i class="fas fa-bolt"></i>
                                Instant confirmation
                            </div>
                            <div class="add-info-item">
                                <i class="fas fa-mobile-alt"></i>
                                Pay via EasyPaisa / JazzCash
                            </div>
                            @if($salon->phone)
                            <div class="add-info-item">
                                <i class="fas fa-phone"></i>
                                {{ $salon->phone }}
                            </div>
                            @endif
                            @if($salon->email)
                            <div class="add-info-item">
                                <i class="fas fa-envelope"></i>
                                {{ $salon->email }}
                            </div>
                            @endif
                        </div>
                    </div>
 
                    @if(isset($similarSalons) && $similarSalons->count())
                    <div class="nearby-section" style="margin-top:32px;padding-top:32px;border-top:1px solid #f0f0f0;">
                        <h3>Venues nearby</h3>
                        <div class="nearby-grid">
                            @foreach($similarSalons->take(3) as $nearby)
                            <a href="{{ route('salons.show', $nearby->slug) }}" class="nearby-card">
                                <div class="nc-img">
                                    <img src="{{ $nearby->cover_url }}" alt="{{ $nearby->name }}"
                                         onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=300&q=60'">
                                    <button class="nc-fav" onclick="event.preventDefault()"><i class="fas fa-heart"></i></button>
                                </div>
                                <div class="nc-body">
                                    <div class="nc-name">{{ $nearby->name }}</div>
                                    <div class="nc-meta">
                                        <i class="fas fa-star" style="color:#ffc107;font-size:0.72rem;"></i>
                                        {{ number_format($nearby->rating,1) }} · {{ $nearby->city }}
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
 
                    <div class="other-biz-section" style="margin-top:32px;padding-top:32px;border-top:1px solid #f0f0f0;">
                        <h3>Other services in {{ $salon->city }}</h3>
                        <a href="{{ route('salons.index',['city'=>$salon->city]) }}" class="other-biz-btn">
                            Other businesses in {{ $salon->city }}
                        </a>
                        <div class="other-biz-grid">
                            @foreach(['Lash Lifts','Eyebrow Shaping','Beauty Salons','Eyebrows & Lashes','Eyebrow Wax','Face Waxing','Hair Coloring','Hair Colouring Highlights','Eyelash Extensions','Men\'s Haircuts','Hair Salons','Hair Perms','Eyebrow Lamination','Lash Lift and Tint','Children\'s Haircuts','Nail Salons','Massages','Hair Treatments','Pedicures','Medspas','Eyelash Tinting','Blow Dries','Eyebrow Tinting','Women\'s Haircuts','Dermaplaning','Hair Transplants','Bridal Makeup','Mehndi Artists','Waxing Salons','Spas & Saunas'] as $biz)
                            <a href="{{ route('salons.index',['city'=>$salon->city,'search'=>$biz]) }}">{{ $biz }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
 
            {{-- ===== FEATURES SECTION ===== --}}
            <div id="section-features" class="page-section">
                <h3>Salon Features</h3>
 
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach([
                        ['fa-wifi', 'Free Wi-Fi', 'Stay connected while you wait'],
                        ['fa-parking', 'Free Parking', 'Ample parking space available'],
                        ['fa-mug-hot', 'Complimentary Beverages', 'Tea, coffee & water'],
                        ['fa-credit-card', 'Multiple Payment Options', 'Cash, Card, EasyPaisa, JazzCash'],
                        ['fa-hand-sparkles', 'Premium Products', 'Branded & certified products'],
                        ['fa-calendar-check', 'Instant Confirmation', 'Book & get confirmed instantly'],
                    ] as [$icon, $title, $desc])
                    <div style="display:flex;align-items:center;gap:15px;padding:12px;background:#f9f9f9;border-radius:12px;">
                        <div style="width:50px;height:50px;background:#e8e4f5;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas {{ $icon }}" style="color:#7c3aed;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;margin-bottom:4px;">{{ $title }}</div>
                            <div style="font-size:0.8rem;color:#888;">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
 
        </div>{{-- end left --}}
 
        {{-- RIGHT: Sticky Booking Sidebar --}}
        <div class="d-none d-lg-block">
            <div class="booking-sidebar">
                <div class="bs-name">{{ $salon->name }}</div>
 
                <div class="bs-rating">
                    <span class="num">{{ number_format($salon->rating,1) }}</span>
                    <span class="stars">
                        @for($i=1;$i<=5;$i++){{ $i<=round($salon->rating)?'★':'☆' }}@endfor
                    </span>
                    <span class="count" onclick="scrollToSection('reviews')">({{ $salon->total_reviews * 10 + 250 }})</span>
                </div>
 
                @if($salon->is_featured)
                <div class="bs-featured">Featured</div>
                @endif
 
                <a href="{{ route('booking.step1', $salon->id) }}" class="btn-book-now">
                    Book now
                </a>
 
                <div class="bs-info-row" onclick="toggleHours()">
                    <i class="fas fa-clock" style="color:#E91E8C;"></i>
                    <div class="bs-info-text">
                        @if($isOpen)
                        <span class="status-open">Open</span>
                        @else
                        <span class="status-closed">Closed</span>
                        @endif
                        @if($openTime && $closeTime)
                        – {{ $openTime->format('g:i A') }} – {{ $closeTime->format('g:i A') }}
                        @endif
                        <span class="bs-toggle">˅</span>
                    </div>
                </div>
                <div class="bs-hours-expand" id="bsHoursPanel">
                    @foreach($days as $day)
                    @php $isWD = in_array($day,$workingDays)||in_array(strtolower($day),array_map('strtolower',$workingDays)); @endphp
                    <div class="bs-hours-row {{ $day===$todayName?'today':'' }}">
                        <span>{{ $day }}</span>
                        <span>{{ $isWD ? (($salon->open_time ? \Carbon\Carbon::parse($salon->open_time)->format('g:i A') : '10:00 AM').' – '.($salon->close_time ? \Carbon\Carbon::parse($salon->close_time)->format('g:i A') : '8:00 PM')) : 'Closed' }}</span>
                    </div>
                    @endforeach
                </div>
 
                <div class="bs-info-row">
                    <i class="fas fa-map-marker-alt" style="color:#E91E8C;"></i>
                    <div class="bs-info-text">
                        {{ $salon->address }}, {{ $salon->city }}
                        <br>
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="directions-link">Get directions</a>
                    </div>
                </div>
 
                @if($salon->paymentDetails->count())
                <div class="bs-info-row">
                    <i class="fas fa-mobile-alt" style="color:#7c3aed;"></i>
                    <div class="bs-info-text">
                        @foreach($salon->paymentDetails as $pd)
                        {{ $pd->method_label }}{{ !$loop->last ? ' & ' : '' }}
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
 
    </div>{{-- end content-grid --}}
 
    {{-- Mobile Book Now button --}}
    <div class="d-lg-none" style="position:fixed;bottom:0;left:0;right:0;padding:12px 16px;background:#fff;border-top:1px solid #f0f0f0;z-index:500;">
        <a href="{{ route('booking.step1', $salon->id) }}" style="display:block;text-align:center;background:#1a1a1a;color:#fff;border-radius:12px;padding:14px;font-size:1rem;font-weight:700;">
            Book now
        </a>
    </div>
 
</div>{{-- end detail-wrap --}}
 
{{-- Photo Modal --}}
<div class="photo-modal" id="photoModal" onclick="closePhoto()">
    <div class="photo-modal-inner" onclick="event.stopPropagation()">
        <button class="photo-modal-close" onclick="closePhoto()"><i class="fas fa-times"></i></button>
        <img id="modalImg" src="" alt="">
    </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ========================
// ✅ SCROLL-TO-SECTION (replaces old show/hide tab switching)
// All sections stay visible; clicking a tab just scrolls smoothly
// to that section's heading, and highlights the matching tab.
// ========================
const sectionIds = ['services','team','reviews','about','features'];
 
function scrollToSection(section) {
    const el = document.getElementById('section-' + section);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
 
function setActiveTabFromScroll() {
    const tabsBarHeight = document.getElementById('salonTabs').offsetHeight + 70;
    let current = sectionIds[0];
    for (const id of sectionIds) {
        const el = document.getElementById('section-' + id);
        if (el && el.getBoundingClientRect().top - tabsBarHeight <= 0) {
            current = id;
        }
    }
    document.querySelectorAll('.salon-tab').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.section === current);
    });
}
 
window.addEventListener('scroll', setActiveTabFromScroll, { passive: true });
 
// ========================
// SERVICE CATEGORY FILTER (unchanged — filters within the Services section only)
// ========================
function filterServiceCat(cat, btn) {
    document.querySelectorAll('.svc-cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.service-row').forEach(row => {
        row.style.display = (cat === 'all' || row.dataset.cat === cat) ? 'flex' : 'none';
    });
}
 
// ========================
// HOURS TOGGLE
// ========================
function toggleHours() {
    const panel = document.getElementById('bsHoursPanel');
    panel.classList.toggle('show');
}
 
// ========================
// PHOTO MODAL
// ========================
function openPhoto(url) {
    document.getElementById('modalImg').src = url;
    document.getElementById('photoModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closePhoto() {
    document.getElementById('photoModal').classList.remove('show');
    document.body.style.overflow = '';
}
 
// ========================
// MOBILE MENU
// ========================
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}
 
document.addEventListener('click', e => {
    const menu = document.getElementById('mobileMenu');
    if (!e.target.closest('nav') && !e.target.closest('#mobileMenu')) {
        menu.style.display = 'none';
    }
});
</script>
</body>
</html>