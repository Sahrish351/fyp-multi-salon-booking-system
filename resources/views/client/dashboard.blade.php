{{-- ============================================================ --}}
{{-- FILE: resources/views/client/dashboard.blade.php            --}}
{{-- Glamora Client — Full Width Luxury Pink Dashboard           --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Dashboard — Glamora')
 
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ─── RESET & ROOT ──────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --pink:      #E91E8C;
    --pink-dk:   #c2185b;
    --pink-md:   #F06292;
    --pink-lt:   #FFF0F7;
    --pink-bg:   #FCE4EC;
    --cream:     #FDF9F6;
    --white:     #FFFFFF;
    --gold:      #C9954A;
    --gold-lt:   #FFF8EE;
    --green:     #1B8A5A;
    --green-lt:  #E6F7F0;
    --orange:    #E07B39;
    --orange-lt: #FEF3EC;
    --red:       #DC2626;
    --red-lt:    #FEF2F2;
    --blue:      #3B82F6;
    --blue-lt:   #EEF4FF;
    --purple:    #7C3AED;
    --purple-lt: #F3EEFF;
    --text:      #1C1C2E;
    --text-mid:  #64748B;
    --text-lt:   #9CA3AF;
    --border:    #F0E8F5;
    --shadow:    0 2px 16px rgba(233,30,140,0.06);
    --shadow-md: 0 8px 32px rgba(233,30,140,0.1);
    --sidebar-w: 210px;
}
 
@import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

* {
    font-family: 'Inter', sans-serif;
}

body {
    font-family: 'Inter', sans-serif !important;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.5;
}
 
/* ─── LAYOUT WRAPPER ────────────────────────────────────────── */
.dash-layout {
    display: flex;
    min-height: 100vh;
}
 
/* ─── SIDEBAR ───────────────────────────────────────────────── */
.gl-sidebar {
    width: var(--sidebar-w);
    background: var(--white);
    border-right: 1.5px solid var(--border);
    position: fixed;
    top: 0; left: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    z-index: 200;
    overflow-y: auto;
    flex-shrink: 0;
}
.gl-sidebar::-webkit-scrollbar { width: 3px; }
.gl-sidebar::-webkit-scrollbar-thumb { background: var(--pink-bg); }
 
.sb-brand {
    padding: 1.4rem 1.2rem 1rem;
    border-bottom: 1px solid var(--border);
}
.sb-brand-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1.1;
    letter-spacing: -.2px;
}
.sb-brand-name span { color: var(--pink); font-style: italic; }
.sb-brand-sub { font-size: .65rem; letter-spacing: 1.8px; text-transform: uppercase; color: var(--text-lt); margin-top: 3px; }
 
.sb-user {
    padding: .85rem 1.2rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .65rem;
}
.sb-av {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--pink), var(--pink-dk));
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; font-weight: 700; color: #fff; overflow: hidden;
    border: 2px solid var(--pink-bg);
}
.sb-av img { width: 100%; height: 100%; object-fit: cover; }
.sb-uname { font-size: .8rem; font-weight: 600; color: var(--text); line-height: 1.2; }
.sb-urole { font-size: .65rem; color: var(--pink); font-weight: 500; }
 
.sb-nav { flex: 1; padding: .6rem 0; }
.sb-section { padding: .55rem 1.2rem .2rem; font-size: .58rem; letter-spacing: 2px; text-transform: uppercase; color: var(--text-lt); font-weight: 700; }
.sb-item {
    display: flex; align-items: center; gap: .65rem;
    padding: .58rem 1.2rem;
    font-size: .8rem; font-weight: 500;
    color: var(--text-mid);
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all .15s;
    position: relative;
}
.sb-item i { width: 16px; text-align: center; font-size: .8rem; opacity: .8; }
.sb-item:hover { background: var(--pink-lt); color: var(--pink-dk); border-left-color: var(--pink-md); }
.sb-item.active { background: var(--pink-lt); color: var(--pink-dk); border-left-color: var(--pink); font-weight: 600; }
.sb-item.active i { opacity: 1; }
.sb-badge {
    margin-left: auto;
    background: var(--pink);
    color: #fff;
    font-size: .58rem; font-weight: 700;
    padding: 1px 6px; border-radius: 20px;
    min-width: 17px; text-align: center;
}
 
.sb-bottom {
    border-top: 1px solid var(--border);
    padding: .5rem 0;
}
.sb-logout {
    display: flex; align-items: center; gap: .65rem;
    padding: .58rem 1.2rem;
    font-size: .8rem; font-weight: 500;
    color: #EF4444;
    text-decoration: none;
    transition: background .15s;
    border: none; background: none; width: 100%; cursor: pointer;
}
.sb-logout i { width: 16px; text-align: center; }
.sb-logout:hover { background: #FEF2F2; }
 
/* ─── MAIN AREA ─────────────────────────────────────────────── */
.gl-main {
    margin-left: var(--sidebar-w);
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}
 
/* ─── TOP NAV ───────────────────────────────────────────────── */
.gl-topnav {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: .75rem 1.75rem;
    display: flex; align-items: center; gap: 1rem;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 1px 0 var(--border);
}
.tn-search {
    flex: 1; max-width: 340px;
    background: var(--cream);
    border: 1px solid var(--border);
    border-radius: 50px;
    display: flex; align-items: center; gap: .5rem;
    padding: .45rem .9rem;
}
.tn-search input { border: none; background: none; outline: none; font-size: .8rem; width: 100%; font-family: 'DM Sans',sans-serif; color: var(--text); }
.tn-search i { color: var(--text-lt); font-size: .78rem; }
.tn-actions { margin-left: auto; display: flex; align-items: center; gap: .7rem; }
.tn-btn {
    width: 34px; height: 34px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: var(--cream); border: 1px solid var(--border);
    color: var(--text-mid); font-size: .78rem;
    text-decoration: none; position: relative; transition: all .15s;
}
.tn-btn:hover { border-color: var(--pink); color: var(--pink); }
.tn-notif {
    position: absolute; top: -3px; right: -3px;
    background: var(--pink); color: #fff;
    font-size: .5rem; font-weight: 700;
    width: 14px; height: 14px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}
.tn-avatar { width: 34px; height: 34px; border-radius: 50%; border: 2px solid var(--pink); overflow: hidden; cursor: pointer; }
.tn-avatar img { width: 100%; height: 100%; object-fit: cover; }
.tn-avatar-ph {
    width: 100%; height: 100%;
    background: linear-gradient(135deg,var(--pink),var(--pink-dk));
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 700; color: #fff;
}
 
/* ─── PAGE BODY ─────────────────────────────────────────────── */
.gl-body {
    padding: 1.5rem 1.75rem;
    flex: 1;
}
 
/* ─── WELCOME CARD ──────────────────────────────────────────── */
.wc {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 1.6rem 2rem;
    display: flex; align-items: stretch; gap: 1.5rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow);
    overflow: hidden;
}
.wc-text { flex: 1; }
.wc-greeting {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.7rem; font-weight: 700;
    color: var(--text); line-height: 1.2; margin-bottom: .35rem;
    letter-spacing: -.2px;
}
.wc-sub { font-size: .84rem; color: var(--text-mid); margin-bottom: 1rem; line-height: 1.5; }
.wc-prog-lbl { display: flex; justify-content: space-between; font-size: .72rem; color: var(--text-mid); margin-bottom: .35rem; font-weight: 500; }
.wc-prog-bar { height: 5px; background: var(--pink-bg); border-radius: 3px; overflow: hidden; }
.wc-prog-fill { height: 100%; background: linear-gradient(90deg,var(--pink),var(--pink-dk)); border-radius: 3px; transition: width .8s ease; }
.wc-cta {
    display: inline-flex; align-items: center; gap: 5px;
    margin-top: .7rem; font-size: .75rem; font-weight: 700;
    color: var(--pink-dk); text-decoration: none; letter-spacing: .5px;
    text-transform: uppercase;
}
.wc-cta:hover { color: var(--pink); }
.wc-img {
    width: 160px; border-radius: 16px; overflow: hidden; flex-shrink: 0;
    background: linear-gradient(135deg, var(--pink-lt), var(--pink-bg));
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem;
}
.wc-img img { width: 100%; height: 100%; object-fit: cover; }
 
/* ─── MINI STATS ─────────────────────────────────────────────── */
.ms-grid {
    display: grid;
    grid-template-columns: repeat(6,1fr);
    gap: .85rem;
    margin-bottom: 1.25rem;
}
.ms-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: .9rem .7rem;
    text-align: center;
    box-shadow: var(--shadow);
    transition: transform .2s;
}
.ms-card:hover { transform: translateY(-3px); }
.ms-ico {
    width: 38px; height: 38px; border-radius: 11px;
    background: var(--pink-lt); color: var(--pink);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .55rem; font-size: .82rem;
}
.ms-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.5rem; font-weight: 700; color: var(--text); line-height: 1;
}
.ms-lbl { font-size: .6rem; color: var(--text-lt); text-transform: uppercase; letter-spacing: .8px; margin-top: 2px; font-weight: 600; }
 
/* ─── ACTION BTNS ───────────────────────────────────────────── */
.ab-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: .85rem;
    margin-bottom: 1.25rem;
}
.ab-btn {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: .9rem .75rem;
    text-align: center; text-decoration: none; color: var(--text);
    display: flex; flex-direction: column; align-items: center; gap: .5rem;
    transition: all .2s; box-shadow: var(--shadow);
}
.ab-btn:hover { border-color: var(--pink); background: var(--pink-lt); color: var(--pink-dk); box-shadow: var(--shadow-md); }
.ab-btn.ab-primary { background: linear-gradient(135deg,var(--pink),var(--pink-dk)); border-color: transparent; color: #fff; box-shadow: 0 6px 20px rgba(233,30,140,0.28); }
.ab-btn.ab-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(233,30,140,0.38); color: #fff; }
.ab-ico { width: 42px; height: 42px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: .92rem; }
.ab-primary .ab-ico { background: rgba(255,255,255,.2); color: #fff; }
.ab-btn:not(.ab-primary) .ab-ico { background: var(--pink-lt); color: var(--pink); }
.ab-lbl { font-size: .76rem; font-weight: 600; }
 
/* ─── MAIN GRID ─────────────────────────────────────────────── */
.dash-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1.25rem;
    align-items: start;
}
 
/* ─── CARD ──────────────────────────────────────────────────── */
.g-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow);
    margin-bottom: 1.25rem;
}
.g-card:last-child { margin-bottom: 0; }
.g-hdr {
    padding: .95rem 1.4rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--white);
}
.g-title {
    font-size: .88rem; font-weight: 700; color: var(--text);
    display: flex; align-items: center; gap: 7px; margin: 0;
}
.g-title i { color: var(--pink); font-size: .82rem; }
.g-sub { font-size: .7rem; color: var(--text-lt); margin-top: 1px; }
.g-link {
    font-size: .73rem; font-weight: 600; color: var(--pink-dk);
    text-decoration: none; display: flex; align-items: center; gap: 3px;
    transition: color .15s;
}
.g-link:hover { color: var(--pink); }
 
/* ─── NEXT APPOINTMENT ───────────────────────────────────────── */
.na-wrap { padding: 1.1rem 1.4rem; display: flex; gap: 1.1rem; align-items: flex-start; }
.na-img {
    width: 90px; height: 72px; border-radius: 12px; flex-shrink: 0; overflow: hidden;
    background: linear-gradient(135deg, var(--pink-lt), var(--pink-bg));
    display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
}
.na-img img { width: 100%; height: 100%; object-fit: cover; }
.na-info { flex: 1; min-width: 0; }
.na-salon { font-weight: 700; font-size: .9rem; margin-bottom: 1px; }
.na-loc { font-size: .72rem; color: var(--text-lt); margin-bottom: .55rem; display: flex; align-items: center; gap: 4px; }
.na-loc i { color: var(--pink); font-size: .65rem; }
.na-meta { display: flex; flex-wrap: wrap; gap: .4rem .85rem; margin-bottom: .75rem; }
.na-meta-i { font-size: .72rem; color: var(--text-mid); display: flex; align-items: center; gap: 4px; }
.na-meta-i i { color: var(--pink); font-size: .65rem; }
.na-btns { display: flex; gap: .55rem; }
.na-btn {
    padding: 6px 14px; border-radius: 8px; font-size: .75rem; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none; transition: all .15s;
}
.na-btn-out { background: #fff; border: 1.5px solid var(--border); color: var(--text-mid); }
.na-btn-out:hover { border-color: var(--pink); color: var(--pink); }
.na-btn-pink { background: var(--pink); color: #fff; box-shadow: 0 4px 12px rgba(233,30,140,0.25); }
.na-btn-pink:hover { background: var(--pink-dk); color: #fff; }
.na-status { background: var(--pink-bg); color: var(--pink-dk); font-size: .62rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; letter-spacing: .5px; text-transform: uppercase; }
 
/* ─── PILLS ──────────────────────────────────────────────────── */
.pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: .67rem; font-weight: 700; }
.pill-green  { background: var(--green-lt);  color: var(--green); }
.pill-orange { background: var(--orange-lt); color: var(--orange); }
.pill-red    { background: var(--red-lt);    color: var(--red); }
.pill-pink   { background: var(--pink-lt);   color: var(--pink-dk); }
.pill-blue   { background: var(--blue-lt);   color: var(--blue); }
.pill-purple { background: var(--purple-lt); color: var(--purple); }
.pill-grey   { background: var(--cream);     color: var(--text-mid); border: 1px solid var(--border); }
.pill-mu     { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; }
 
/* ─── WAITLIST TABLE ─────────────────────────────────────────── */
.g-table { width: 100%; border-collapse: collapse; }
.g-table thead th { padding: .6rem 1.4rem; font-size: .63rem; letter-spacing: 1.2px; text-transform: uppercase; color: var(--pink); font-weight: 700; background: var(--pink-lt); border-bottom: 1px solid var(--border); text-align: left; }
.g-table tbody td { padding: .8rem 1.4rem; font-size: .8rem; color: var(--text); border-bottom: 1px solid #FDF5FB; vertical-align: middle; }
.g-table tbody tr:last-child td { border-bottom: none; }
.g-table tbody tr:hover td { background: var(--pink-lt); }
.wl-pos { width: 28px; height: 28px; border-radius: 50%; background: var(--pink-bg); color: var(--pink-dk); display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 800; }
 
/* ─── CATEGORIES ─────────────────────────────────────────────── */
.cat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: .7rem; padding: 1.1rem 1.4rem; }
.cat-item { text-align: center; padding: .85rem .5rem; border-radius: 14px; border: 1.5px solid var(--border); text-decoration: none; color: var(--text); transition: all .2s; }
.cat-item:hover { border-color: var(--pink); background: var(--pink-lt); color: var(--pink-dk); transform: translateY(-2px); }
.cat-ico { font-size: 1.4rem; margin-bottom: .35rem; display: block; }
.cat-lbl { font-size: .72rem; font-weight: 600; }
 
/* ─── REVIEWS ────────────────────────────────────────────────── */
.rev-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; padding: 1.1rem 1.4rem; }
.rev-item { background: var(--cream); border-radius: 14px; padding: .95rem; border: 1px solid var(--border); }
.rev-stars { color: var(--gold); font-size: .72rem; margin-bottom: .2rem; }
.rev-date { font-size: .65rem; color: var(--text-lt); }
.rev-text { font-size: .78rem; color: var(--text-mid); line-height: 1.55; margin: .4rem 0 .7rem; font-style: italic; }
.rev-salon-row { display: flex; align-items: center; gap: .45rem; }
.rev-av { width: 22px; height: 22px; border-radius: 50%; background: var(--pink-lt); display: flex; align-items: center; justify-content: center; font-size: .62rem; font-weight: 700; color: var(--pink-dk); flex-shrink: 0; }
.rev-sname { font-size: .72rem; font-weight: 600; }
 
/* ─── EXPLORE ────────────────────────────────────────────────── */
.exp-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: .85rem; padding: 1.1rem 1.4rem; }
.exp-card { border-radius: 14px; overflow: hidden; border: 1px solid var(--border); text-decoration: none; color: var(--text); transition: all .2s; }
.exp-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.exp-img { height: 88px; overflow: hidden; position: relative; background: var(--pink-lt); display: flex; align-items: center; justify-content: center; font-size: 2rem; }
.exp-img img { width: 100%; height: 100%; object-fit: cover; }
.exp-fav { position: absolute; top: 7px; right: 7px; width: 24px; height: 24px; background: rgba(255,255,255,.85); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .68rem; color: var(--text-lt); }
.exp-rating { position: absolute; bottom: 6px; left: 6px; background: rgba(255,255,255,.9); border-radius: 20px; padding: 1px 6px; font-size: .62rem; font-weight: 700; }
.exp-rating i { color: var(--gold); }
.exp-body { padding: .7rem .75rem; }
.exp-name { font-size: .78rem; font-weight: 700; margin-bottom: 1px; }
.exp-type { font-size: .67rem; color: var(--text-lt); margin-bottom: .2rem; }
.exp-price { font-size: .7rem; font-weight: 600; color: var(--pink-dk); }
 
/* ─── SIDE CALENDAR ──────────────────────────────────────────── */
.cal-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 18px; padding: 1.1rem; box-shadow: var(--shadow); margin-bottom: 1.25rem; }
.cal-hdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: .75rem; }
.cal-title { font-size: .85rem; font-weight: 700; }
.cal-nav { display: flex; gap: .2rem; }
.cal-nav button { width: 26px; height: 26px; border-radius: 50%; border: 1px solid var(--border); background: #fff; cursor: pointer; font-size: .65rem; color: var(--text-mid); display: flex; align-items: center; justify-content: center; transition: all .15s; }
.cal-nav button:hover { border-color: var(--pink); color: var(--pink); }
.cal-dgrid { display: grid; grid-template-columns: repeat(7,1fr); gap: 2px; }
.cal-dlbl { font-size: .58rem; color: var(--text-lt); font-weight: 600; text-align: center; padding: .2rem; }
.cal-d { font-size: .7rem; text-align: center; padding: .28rem .1rem; border-radius: 7px; cursor: pointer; color: var(--text-mid); transition: background .15s; }
.cal-d:hover { background: var(--pink-lt); color: var(--pink-dk); }
.cal-d.today { background: var(--pink); color: #fff; font-weight: 700; }
.cal-d.has-a { color: var(--pink-dk); font-weight: 700; }
.cal-d.empty { cursor: default; }
.cal-events { margin-top: .75rem; display: flex; flex-direction: column; gap: .4rem; }
.cal-ev { background: var(--pink-lt); border-left: 3px solid var(--pink); border-radius: 7px; padding: .45rem .7rem; display: flex; gap: .5rem; align-items: center; }
.cal-ev-time { font-size: .68rem; font-weight: 700; color: var(--pink-dk); white-space: nowrap; }
.cal-ev-name { font-size: .68rem; color: var(--text-mid); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
 
.pay-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 18px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 1.25rem; }
.pay-item { display: flex; align-items: center; gap: .7rem; padding: .7rem 1.1rem; border-bottom: 1px solid #FDF5FB; }
.pay-item:last-of-type { border-bottom: none; }
.pay-dot { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .7rem; }
.pd-green  { background: var(--green-lt);  color: var(--green); }
.pd-orange { background: var(--orange-lt); color: var(--orange); }
.pd-red    { background: var(--red-lt);    color: var(--red); }
.pay-name  { font-size: .77rem; font-weight: 600; }
.pay-date  { font-size: .65rem; color: var(--text-lt); }
.pay-all { display: block; text-align: center; padding: .6rem; font-size: .75rem; font-weight: 600; color: var(--pink-dk); text-decoration: none; border-top: 1px solid var(--border); transition: background .15s; }
.pay-all:hover { background: var(--pink-lt); }
 
.fav-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 18px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 1.25rem; }
.fav-item { display: flex; align-items: center; gap: .7rem; padding: .7rem 1.1rem; border-bottom: 1px solid #FDF5FB; transition: background .15s; }
.fav-item:last-child { border-bottom: none; }
.fav-item:hover { background: var(--pink-lt); }
.fav-img { width: 46px; height: 40px; border-radius: 9px; background: var(--pink-lt); display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0; overflow: hidden; }
.fav-img img { width: 100%; height: 100%; object-fit: cover; }
.fav-name { font-size: .78rem; font-weight: 600; }
.fav-rating { font-size: .65rem; color: var(--text-lt); margin-top: 1px; }
.fav-rating i { color: var(--gold); font-size: .6rem; }
.fav-heart { margin-left: auto; color: var(--pink); font-size: .78rem; }
 
.act-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 18px; overflow: hidden; box-shadow: var(--shadow); }
.act-item { display: flex; align-items: flex-start; gap: .65rem; padding: .7rem 1.1rem; border-bottom: 1px solid #FDF5FB; }
.act-item:last-child { border-bottom: none; }
.act-dot { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .68rem; flex-shrink: 0; margin-top: 1px; }
.act-text { font-size: .75rem; font-weight: 500; color: var(--text); line-height: 1.4; }
.act-sub  { font-size: .67rem; color: var(--text-lt); margin-top: 1px; }
.act-time { font-size: .62rem; color: var(--text-lt); white-space: nowrap; flex-shrink: 0; margin-top: 4px; }
 
.empty-box { text-align: center; padding: 2rem 1rem; color: var(--text-lt); }
.empty-box i { font-size: 2rem; color: var(--pink-bg); margin-bottom: .6rem; display: block; }
.empty-box a { color: var(--pink); text-decoration: none; font-weight: 600; }
 
@media(max-width:1100px) { .dash-grid { grid-template-columns: 1fr; } .exp-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:900px)  { .ms-grid { grid-template-columns: repeat(3,1fr); } .ab-grid { grid-template-columns: repeat(2,1fr); } .rev-grid { grid-template-columns: 1fr; } }
@media(max-width:768px)  { .gl-sidebar { transform: translateX(-100%); } .gl-main { margin-left: 0; } .ms-grid { grid-template-columns: repeat(2,1fr); } .cat-grid { grid-template-columns: repeat(2,1fr); } }
 
@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.fu   { animation: fadeUp .4s ease both; }
.d1   { animation-delay:.06s; } .d2 { animation-delay:.12s; }
.d3   { animation-delay:.18s; } .d4 { animation-delay:.24s; }
.d5   { animation-delay:.30s; }
</style>
@endpush
 
@section('content')
 
<div class="gl-topnav">
    <div class="tn-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search services or salons...">
    </div>
    <div class="tn-actions">
        {{-- ========== NOTIFICATION BELL - FIXED ========== --}}
        <a href="#" class="tn-btn" style="text-decoration:none;">
            <i class="fas fa-bell"></i>
            @php 
                use Illuminate\Support\Facades\DB;
                $unreadCount = DB::table('notifications')
                    ->where(function($q) {
                        $q->where('recipient_type', 'client')
                          ->orWhere('recipient_type', 'all');
                    })
                    ->where('sent', 1)
                    ->whereNull('read_at')
                    ->count();
            @endphp
            @if($unreadCount > 0)
                <span class="tn-notif">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
            @endif