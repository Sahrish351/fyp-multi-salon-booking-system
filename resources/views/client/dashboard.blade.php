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
        <a href="{{ route('client.notifications.index') }}" class="tn-btn" style="text-decoration:none;">
            <i class="fas fa-bell"></i>
            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
            @if($unread > 0)<span class="tn-notif">{{ $unread }}</span>@endif
        </a>
        <a href="{{ route('client.favorites.index') }}" class="tn-btn" style="text-decoration:none;"><i class="fas fa-heart"></i></a>
        <a href="{{ route('client.appointments.index') }}" class="tn-btn" style="text-decoration:none;"><i class="fas fa-calendar"></i></a>
        <div class="tn-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar_url }}" alt="">
            @else
                <div class="tn-avatar-ph">{{ substr(auth()->user()->name,0,1) }}</div>
            @endif
        </div>
    </div>
</div>
 
{{-- ── BODY ───────────────────────────────────────────────────── --}}
<div class="gl-body">
 
    {{-- WELCOME ─────────────────────────────────────────────── --}}
    <div class="wc fu">
        <div class="wc-text">
            <h2 class="wc-greeting">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ',auth()->user()->name)[0] }}.
            </h2>
            <p class="wc-sub">Welcome back to your luxury sanctuary. You have <strong>{{ $stats['upcoming'] ?? 0 }}</strong> appointments scheduled for today.</p>
            @php
                $u = auth()->user();
                $fields = [$u->name,$u->email,$u->phone,$u->city,$u->avatar];
                $pct = round(collect($fields)->filter()->count()/count($fields)*100);
            @endphp
            <div class="wc-prog-lbl"><span>Profile Completion</span><span>{{ $pct }}%</span></div>
            <div class="wc-prog-bar"><div class="wc-prog-fill" style="width:{{ $pct }}%"></div></div>
            <a href="{{ route('client.profile.index') }}" class="wc-cta">Complete Profile →</a>
        </div>
        <div class="wc-img">🌸</div>
    </div>
 
    {{-- MINI STATS ────────────────────────────────────────────── --}}
    @php
        $upcoming  = ($appointments ?? collect())->where('status','confirmed')->where('appointment_date','>=',today())->count();
        $pending   = ($appointments ?? collect())->whereIn('status',['pending_payment','payment_submitted'])->count();
        $wlCount   = $waitlistCount ?? 0;
        $favCount  = $favoritesCount ?? 0;
        $alerts    = auth()->user()->unreadNotifications->count();
    @endphp
    <div class="ms-grid fu d1">
        @foreach([
    ['fa-calendar-check', $upcoming, 'Upcoming'],
    ['fa-clock', $pending, 'Pending'],
    ['fa-list', $wlCount, 'Waitlist'],
    ['fa-heart', $favCount, 'Favorites'],
    ['fa-credit-card', 0, 'Payments'],
    ['fa-bell', $alerts, 'Alerts'],
] as $i => [$ico, $num, $lbl])
        <div class="ms-card">
            <div class="ms-ico"><i class="fas {{ $ico }}"></i></div>
            <div class="ms-num">{{ str_pad($num,2,'0',STR_PAD_LEFT) }}</div>
            <div class="ms-lbl">{{ $lbl }}</div>
        </div>
        @endforeach
    </div>
 
    {{-- ACTION BUTTONS ────────────────────────────────────────── --}}
    <div class="ab-grid fu d2">
        <a href="{{ route('salons.index') }}" class="ab-btn ab-primary">
            <div class="ab-ico"><i class="fas fa-plus-circle"></i></div>
            <div class="ab-lbl">Book Appointment</div>
        </a>
        <a href="{{ route('salons.index') }}" class="ab-btn">
            <div class="ab-ico"><i class="fas fa-search"></i></div>
            <div class="ab-lbl">Search Salons</div>
        </a>
        <a href="{{ route('client.appointments.index') }}" class="ab-btn">
            <div class="ab-ico"><i class="fas fa-upload"></i></div>
            <div class="ab-lbl">Upload Payment</div>
        </a>
        <a href="{{ route('client.waitlist.index') }}" class="ab-btn">
            <div class="ab-ico"><i class="fas fa-list-ul"></i></div>
            <div class="ab-lbl">Join Waitlist</div>
        </a>
    </div>
 
    {{-- 2-COL GRID ─────────────────────────────────────────────── --}}
    <div class="dash-grid">
 
        {{-- ── LEFT COL ──────────────────────────────────────────── --}}
        <div>
 
            {{-- Next Appointment --}}
            <div class="g-card fu d3">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-calendar-star"></i> Next Appointment</p>
                    @if($nextAppointment ?? false)<span class="na-status">{{ strtoupper(str_replace('_',' ',$nextAppointment->status)) }}</span>@endif
                </div>
                @if($nextAppointment ?? false)
                <div class="na-wrap">
                    <div class="na-img">
                        @if($nextAppointment->salon->cover_photo)
                        <img src="{{ $nextAppointment->salon->cover_photo_url }}" alt="">
                        @else💆@endif
                    </div>
                    <div class="na-info">
                        <div class="na-salon">{{ $nextAppointment->salon->name }}</div>
                        <div class="na-loc"><i class="fas fa-map-marker-alt"></i>{{ $nextAppointment->salon->address }}, {{ $nextAppointment->salon->city }}</div>
                        <div class="na-meta">
                            <div class="na-meta-i"><i class="fas fa-spa"></i> {{ $nextAppointment->service->name }}</div>
                            <div class="na-meta-i"><i class="fas fa-user"></i> {{ $nextAppointment->stylist->name }}</div>
                            <div class="na-meta-i"><i class="fas fa-calendar"></i> {{ $nextAppointment->appointment_date->format('M d, Y') }}</div>
                            <div class="na-meta-i"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A') }}</div>
                        </div>
                        <div class="na-btns">
                            <a href="{{ route('client.appointments.reschedule',$nextAppointment->id) }}" class="na-btn na-btn-out">Reschedule</a>
                            <a href="https://maps.google.com/?q={{ urlencode($nextAppointment->salon->address) }}" target="_blank" class="na-btn na-btn-pink">Get Directions</a>
                        </div>
                    </div>
                </div>
                @else
                <div class="empty-box"><i class="fas fa-calendar-times"></i><p>No upcoming appointments.<br><a href="{{ route('salons.index') }}">Book one now!</a></p></div>
                @endif
            </div>
 
            {{-- Active Waitlists --}}
            @if(isset($waitlists) && $waitlists->count() > 0)
            <div class="g-card fu d3">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-list-ul"></i> Active Waitlists</p>
                    <a href="{{ route('client.waitlist.index') }}" class="g-link">View All →</a>
                </div>
                <table class="g-table">
                    <thead><tr><th>Position</th><th>Salon Name</th><th>Requested Slot</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($waitlists->take(3) as $w)
                        <tr>
                            <td><div class="wl-pos">#{{ $w->position }}</div></td>
                            <td style="font-weight:600;">{{ $w->salon->name }}</td>
                            <td style="color:var(--text-mid);">{{ $w->preferred_date?->format('l, g:i A') ?? '—' }}</td>
                            <td><span class="pill {{ ['waiting'=>'pill-grey','notified'=>'pill-mu','accepted'=>'pill-green','expired'=>'pill-red'][$w->status]??'pill-grey' }}">{{ ucfirst($w->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
 
            {{-- Explore Categories --}}
            <div class="g-card fu d4">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-th-large"></i> Explore Categories</p>
                    <a href="{{ route('salons.index') }}" class="g-link">SEE ALL</a>
                </div>
                <div class="cat-grid">
                    @foreach([['✂️','Hair'],['💄','Makeup'],['💍','Bridal'],['🛁','Spa'],['💅','Nails'],['🧴','Skincare'],['🌿','Wellness'],['✨','All']] as [$ico,$lbl])
                    <a href="{{ route('salons.index') }}" class="cat-item">
                        <span class="cat-ico">{{ $ico }}</span>
                        <span class="cat-lbl">{{ $lbl }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
 
            {{-- Your Recent Reviews --}}
            @if(isset($myReviews) && $myReviews->count() > 0)
            <div class="g-card fu d4">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-star"></i> Your Recent Reviews</p>
                </div>
                <div class="rev-grid">
                    @foreach($myReviews->take(2) as $rev)
                    <div class="rev-item">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.2rem;">
                            <div class="rev-stars">@for($i=1;$i<=5;$i++)<i class="fas fa-star{{ $i>$rev->rating?'-empty':'' }}"></i>@endfor</div>
                            <div class="rev-date">{{ $rev->created_at->diffForHumans() }}</div>
                        </div>
                        <p class="rev-text">"{{ Str::limit($rev->comment,90) }}"</p>
                        <div class="rev-salon-row">
                            <div class="rev-av">{{ substr($rev->salon->name,0,1) }}</div>
                            <div class="rev-sname">{{ $rev->salon->name }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
 
            {{-- Curated For You --}}
            <div class="g-card fu d5">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-sparkles"></i> Curated for Your Glow</p>
                    <span style="font-size:.7rem;color:var(--text-lt);">Based on your recent services</span>
                </div>
                <div class="exp-grid">
                    @forelse(isset($recommendedSalons) ? $recommendedSalons->take(4) : collect() as $s)
                    <a href="{{ route('salons.show',$s->slug) }}" class="exp-card">
                        <div class="exp-img">
                            @if($s->cover_photo)<img src="{{ $s->cover_photo_url }}" alt="">@else💆@endif
                            <div class="exp-fav"><i class="fas fa-heart"></i></div>
                            <div class="exp-rating"><i class="fas fa-star"></i> {{ number_format($s->rating,1) }}</div>
                        </div>
                        <div class="exp-body">
                            <div class="exp-name">{{ Str::limit($s->name,18) }}</div>
                            <div class="exp-type">{{ $s->category->name ?? 'Salon' }}</div>
                            <div class="exp-price">Rs. {{ number_format($s->services->min('price')) }} – {{ number_format($s->services->max('price')) }}</div>
                        </div>
                    </a>
                    @empty
                    @foreach([['💆','Radiance Medispa','Skincare','1,200–4,500'],['✂️','Gloss & Glamour','Hair','800–3,500'],['💍','The Bridal Room','Bridal','2,000–12,000'],['🧴','Pure Wellness','Spa','900–4,000']] as [$ico,$n,$t,$p])
                    <a href="{{ route('salons.index') }}" class="exp-card">
                        <div class="exp-img"><div style="font-size:2rem;">{{ $ico }}</div><div class="exp-fav"><i class="fas fa-heart"></i></div><div class="exp-rating"><i class="fas fa-star"></i> 4.8</div></div>
                        <div class="exp-body"><div class="exp-name">{{ $n }}</div><div class="exp-type">{{ $t }}</div><div class="exp-price">Rs. {{ $p }}</div></div>
                    </a>
                    @endforeach
                    @endforelse
                </div>
            </div>
        </div>
 
        {{-- ── RIGHT COL ─────────────────────────────────────────── --}}
        <div>
 
            {{-- Calendar --}}
            <div class="cal-card fu d2">
                <div class="cal-hdr">
                    <div class="cal-title">Calendar</div>
                    <div class="cal-nav">
                        <button><i class="fas fa-chevron-left"></i></button>
                        <button><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="cal-dgrid">
                    @foreach(['S','M','T','W','T','F','S'] as $d)<div class="cal-dlbl">{{ $d }}</div>@endforeach
                    @php
                        $today = now();
                        $startDay = $today->copy()->startOfMonth()->dayOfWeek;
                        $daysInMonth = $today->daysInMonth;
                        $apptDays = isset($appointments) ? $appointments->pluck('appointment_date')->map(fn($d)=>$d->day)->toArray() : [];
                    @endphp
                    @for($i=0;$i<$startDay;$i++)<div class="cal-d empty"></div>@endfor
                    @for($d=1;$d<=$daysInMonth;$d++)<div class="cal-d {{ $d==$today->day?'today':'' }} {{ in_array($d,$apptDays)&&$d!=$today->day?'has-a':'' }}">{{ $d }}</div>@endfor
                </div>
                <div class="cal-events">
                    @if(isset($appointments) && $appointments->where('appointment_date',today())->count())
                        @foreach($appointments->where('appointment_date',today())->take(2) as $ev)
                        <div class="cal-ev">
                            <span class="cal-ev-time">{{ \Carbon\Carbon::parse($ev->start_time)->format('g:i A') }}</span>
                            <span class="cal-ev-name">{{ $ev->service->name }} · {{ $ev->salon->name }}</span>
                        </div>
                        @endforeach
                    @else
                    <div class="cal-ev"><span class="cal-ev-time">—</span><span class="cal-ev-name">No appointments today</span></div>
                    @endif
                </div>
            </div>
 
            {{-- Payment Activity --}}
            <div class="pay-card fu d3">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-credit-card"></i> Payment Activity</p>
                </div>
                @forelse(isset($recentPayments) ? $recentPayments->take(3) : collect() as $pay)
                <div class="pay-item">
                    <div class="pay-dot {{ ['approved'=>'pd-green','pending'=>'pd-orange','rejected'=>'pd-red'][$pay->status]??'pd-orange' }}">
                        <i class="fas {{ ['approved'=>'fa-check','pending'=>'fa-clock','rejected'=>'fa-times'][$pay->status]??'fa-clock' }}"></i>
                    </div>
                    <div style="flex:1">
                        <div class="pay-name">{{ $pay->appointment->service->name ?? 'Service' }}</div>
                        <div class="pay-date">{{ $pay->created_at->format('M d') }} · Rs.{{ number_format($pay->amount) }}</div>
                    </div>
                    <span class="pill {{ ['approved'=>'pill-green','pending'=>'pill-orange','rejected'=>'pill-red'][$pay->status]??'pill-orange' }}">{{ ucfirst($pay->status) }}</span>
                </div>
                @empty
                @foreach([['Nail Art Session','Oct 18','Rs.865','approved','pd-green','fa-check','pill-green'],['Bridal Trial Deposit','Today','Rs.1,500','pending','pd-orange','fa-clock','pill-orange'],['Skincare Bundle','Oct 12','Rs.890','rejected','pd-red','fa-times','pill-red']] as [$n,$dt,$a,$s,$dc,$ic,$pc])
                <div class="pay-item">
                    <div class="pay-dot {{ $dc }}"><i class="fas {{ $ic }}"></i></div>
                    <div style="flex:1"><div class="pay-name">{{ $n }}</div><div class="pay-date">{{ $dt }} · {{ $a }}</div></div>
                    <span class="pill {{ $pc }}">{{ ucfirst($s) }}</span>
                </div>
                @endforeach
                @endforelse
                <a href="{{ route('client.appointments.index') }}" class="pay-all">View All Transactions →</a>
            </div>
 
            {{-- Favorite Havens --}}
            <div class="fav-card fu d4">
                <div class="g-hdr">
                    <p class="g-title"><i class="fas fa-heart"></i> Favorite Havens</p>
                    <a href="{{ route('client.favorites.index') }}" class="g-link">All →</a>
                </div>
                @forelse(isset($favorites) ? $favorites->take(3) : collect() as $fav)
                <div class="fav-item">
                    <div class="fav-img">
                        @if($fav->salon->cover_photo)<img src="{{ $fav->salon->cover_photo_url }}" alt="">@else💆@endif
                    </div>
                    <div style="flex:1">
                        <div class="fav-name">{{ $fav->salon->name }}</div>
                        <div class="fav-rating"><i class="fas fa-star"></i> {{ number_format($fav->salon->rating??4.5,1) }}</div>
                    </div>
                    <i class="fas fa-heart fav-heart"></i>
                </div>
                @empty
                @foreach([['Vogue Coiffure','4.9','(21k)'],['Silken Touch Spa','5.0','(840)'],['Bloom Therapeutics','4.7','(1.2k)']] as [$n,$r,$c])
                <div class="fav-item">
                    <div class="fav-img">💆</div>
                    <div style="flex:1"><div class="fav-name">{{ $n }}</div><div class="fav-rating"><i class="fas fa-star"></i> {{ $r }} {{ $c }}</div></div>
                    <i class="fas fa-heart fav-heart"></i>
                </div>
                @endforeach
                @endforelse
            </div>
 
           
 
        </div>{{-- /right col --}}
    </div>{{-- /dash-grid --}}
 
</div>{{-- /gl-body --}}
 
@endsection
 
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fill = document.querySelector('.wc-prog-fill');
    if(fill){ const w=fill.style.width; fill.style.width='0%'; setTimeout(()=>fill.style.width=w,400); }
});
</script>
@endpush