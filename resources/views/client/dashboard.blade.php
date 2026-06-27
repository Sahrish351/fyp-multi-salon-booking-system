{{-- ============================================================ --}}
{{-- FILE: resources/views/client/dashboard.blade.php            --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Dashboard — Glamora')
@section('content')

<div class="ps-4">

<style>
    .card-dashboard {
        background: #ffffff;
        border: 1px solid #fce4ec;
        border-radius: 20px;
        transition: all .3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .card-dashboard:hover {
        border-color: #E91E8C;
        box-shadow: 0 8px 25px rgba(233,30,140,0.08);
        transform: translateY(-2px);
    }
    .btn-pink-gradient {
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: #fff; border: none; font-weight: 600; transition: all 0.3s;
    }
    .btn-pink-gradient:hover {
        background: linear-gradient(135deg, #d81b60, #a31545);
        color: #fff; transform: scale(1.02);
    }
    .btn-pink-outline {
        border: 1px solid #E91E8C; color: #E91E8C;
        background: transparent; font-weight: 600; transition: all 0.3s;
    }
    .btn-pink-outline:hover { background: #E91E8C; color: #fff; }

    /* ── Stat Cards ── */
    .stat-mini {
        border-radius: 14px;
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
        min-height: 90px;
    }
    .stat-mini:hover { transform: translateY(-3px); }
    .stat-mini .s-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; margin-bottom: 8px;
        background: rgba(255,255,255,0.28);
    }
    .stat-mini .s-val {
        font-size: 1.45rem; font-weight: 800; color: #fff;
        line-height: 1; text-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .stat-mini .s-lbl {
        font-size: 0.6rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.8px; color: rgba(255,255,255,0.82); margin-top: 4px;
    }

    /* Muted palette — each card */
    .sm-pink   { background: #E0177D; box-shadow: 0 4px 14px rgba(224,23,125,0.28); }
    .sm-chrome { background: #607D8B; box-shadow: 0 4px 14px rgba(96,125,139,0.28); }
    .sm-purple { background: #7B5EA7; box-shadow: 0 4px 14px rgba(123,94,167,0.28); }
    .sm-xampp  { background: #F26522; box-shadow: 0 4px 14px rgba(242,101,34,0.28); }
    .sm-teal   { background: #0097A7; box-shadow: 0 4px 14px rgba(0,151,167,0.28); }
    .sm-amber  { background: #C9A800; box-shadow: 0 4px 14px rgba(201,168,0,0.28); }
    .sm-amber .s-val,
    .sm-amber .s-lbl { color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.12); }
    .sm-amber .s-lbl { color: rgba(255,255,255,0.82); }

    /* ---- Bar Chart ---- */
    .chart-container {
        height: 150px; display: flex; align-items: flex-end;
        justify-content: space-around; padding: 0 10px; gap: 8px;
    }
    .chart-bar-wrapper { display: flex; flex-direction: column; align-items: center; flex: 1; }
    .chart-bar {
        width: 100%; max-width: 40px;
        background: linear-gradient(180deg, #E91E8C, #c2185b);
        border-radius: 6px 6px 0 0; min-height: 4px; transition: height 0.8s ease;
    }
    .chart-bar-label {
        font-size: 0.6rem; color: #aaa; margin-top: 6px;
        text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px;
    }
    .chart-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 0; }
    .chart-empty i { font-size: 2.5rem; color: rgba(233,30,140,0.12); margin-bottom: 8px; }
    .chart-empty p { color: #bbb; font-size: 0.85rem; margin: 0; }
</style>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-home me-2" style="color:#E91E8C;"></i>
            Welcome back, {{ auth()->user()->first_name ?? explode(' ', auth()->user()->name)[0] }}
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">Here's everything happening with your bookings</p>
    </div>
</div>

{{-- ===== 6 STAT CARDS — small square, muted colors ===== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-pink">
            <div class="s-icon"><i class="fas fa-calendar-check" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['upcoming'] ?? 0 }}</div>
            <div class="s-lbl">Upcoming</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-chrome">
            <div class="s-icon"><i class="fas fa-clock" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['pending'] ?? 0 }}</div>
            <div class="s-lbl">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-purple">
            <div class="s-icon"><i class="fas fa-hourglass-half" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['waitlist'] ?? 0 }}</div>
            <div class="s-lbl">Waitlist</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-xampp">
            <div class="s-icon"><i class="fas fa-comment" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['complaints'] ?? 0 }}</div>
            <div class="s-lbl">Complaints</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-teal">
            <div class="s-icon"><i class="fas fa-credit-card" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['payments'] ?? 0 }}</div>
            <div class="s-lbl">Payments</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-amber">
            <div class="s-icon"><i class="fas fa-bell" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['alerts'] ?? 0 }}</div>
            <div class="s-lbl">Alerts</div>
        </div>
    </div>
</div>

{{-- ===== HERO + QUICK ACTIONS ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-dashboard p-5 h-100 d-flex flex-column justify-content-center text-center">
            <h3 class="fw-bold" style="font-family:'Playfair Display',serif;color:#333;font-size:2.5rem;">Ready for a glow-up?</h3>
            <p class="text-muted" style="font-size:1.1rem;max-width:550px;margin:10px auto 20px;">Our experts are waiting to make you feel extraordinary. Explore our premium treatments and secure your spot today.</p>
            <div>
                <a href="{{ route('salons.index') }}" class="btn btn-lg rounded-pill px-5 btn-pink-gradient">
                    <i class="fas fa-plus-circle me-2"></i> Book Appointment Now
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-dashboard p-4 h-100 d-flex flex-column justify-content-center">
            <h5 class="fw-bold mb-3" style="font-family:'Playfair Display',serif;color:#333;font-size:1.1rem;">Quick Actions</h5>
            <div class="row g-2">
                <div class="col-4">
                    <a href="{{ route('salons.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-store" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Book</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.complaints.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-exclamation-circle" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Complaints</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.appointments.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-calendar-alt" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Schedule</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.favorites.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-bookmark" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Salons</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.notifications.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-bell" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Alerts</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.waitlist.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-hourglass-half" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Waitlist</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== APPOINTMENT OVERVIEW + PAYMENT ACTIVITY ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card-dashboard p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;color:#333;font-size:1.1rem;">Appointment Overview</h5>
                <span class="badge bg-light text-muted px-3 py-2 rounded-pill">Monthly</span>
            </div>
            <div class="text-center py-3">
                <div class="position-relative d-inline-block" style="width:150px;height:150px;">
                    <svg viewBox="0 0 100 100" style="width:100%;height:100%;transform:rotate(-90deg);">
                        <circle cx="50" cy="50" r="40" fill="transparent" stroke="#eee" stroke-width="12" />
                        <circle cx="50" cy="50" r="40" fill="transparent" stroke="#E91E8C" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="251.2" />
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="display-6 fw-bold" style="color:#E91E8C;">{{ $stats['total'] ?? 0 }}</div>
                        <div class="text-uppercase small text-muted" style="letter-spacing:1px;font-size:0.6rem;">Total</div>
                    </div>
                </div>
                <div class="row mt-4 g-1">
                    @php
                        $statuses = [
                            ['label'=>'Completed', 'color'=>'#10b981'],
                            ['label'=>'Confirmed', 'color'=>'#3b82f6'],
                            ['label'=>'Pending',   'color'=>'#7B5EA7'],
                            ['label'=>'Cancelled', 'color'=>'#f43f5e'],
                            ['label'=>'Upcoming',  'color'=>'#F26522'],
                            ['label'=>'Waitlist',  'color'=>'#F9A825'],
                        ];
                    @endphp
                    @foreach($statuses as $s)
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center small border-bottom pb-1" style="font-size:0.7rem;">
                                <span>
                                    <span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:{{ $s['color'] }};"></span>
                                    {{ $s['label'] }}
                                </span>
                                <span class="fw-bold" style="color:{{ $s['color'] }};">0%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-dashboard p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;color:#333;font-size:1.1rem;">Payment Activity</h5>
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;color:#E91E8C;">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size:0.65rem;">Paid Total - 0%</span>
                    <span class="fw-bold" style="color:#E91E8C;">$0.00</span>
                </div>
                <div class="progress" style="height:8px;border-radius:10px;background:#f3f3f4;">
                    <div class="progress-bar" style="width:0%;background:#E91E8C;border-radius:10px;"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size:0.65rem;">Pending Dues - 0%</span>
                    <span class="fw-bold" style="color:#333;">$0.00</span>
                </div>
                <div class="progress" style="height:8px;border-radius:10px;background:#f3f3f4;">
                    <div class="progress-bar" style="width:0%;background:#e2e2e3;border-radius:10px;"></div>
                </div>
            </div>
            <div class="text-center py-3 rounded-4" style="border:2px dashed #fce4ec;background:#fafafa;">
                <i class="fas fa-receipt fa-3x mb-2" style="color:rgba(233,30,140,0.15);"></i>
                <h6 class="fw-bold" style="color:#333;font-size:0.9rem;">No transaction history yet</h6>
                <p class="small text-muted" style="font-size:0.8rem;">Your premium payment summaries will appear here.</p>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <div class="small fw-bold text-uppercase" style="color:#333;font-size:0.65rem;">Flawless Record</div>
                        <div class="small text-muted" style="font-size:0.6rem;">No active complaints on file.</div>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-sm rounded-pill px-4 btn-pink-outline" style="font-size:0.7rem;">Support Hub</a>
            </div>
        </div>
    </div>
</div>

{{-- ===== BOOKING ACTIVITY HISTORY ===== --}}
<div class="card-dashboard p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;color:#333;">
            <i class="fas fa-chart-line me-2" style="color:#E91E8C;"></i> Booking Activity History
        </h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm active" id="filter-weekly"  style="background:#E91E8C;color:#fff;border-radius:50px 0 0 50px;padding:6px 18px;font-weight:600;font-size:0.7rem;border:none;">Weekly</button>
            <button type="button" class="btn btn-sm btn-outline-secondary"      id="filter-monthly" style="border-radius:0;padding:6px 18px;font-weight:600;font-size:0.7rem;border-color:#ddd;color:#888;">Monthly</button>
            <button type="button" class="btn btn-sm btn-outline-secondary"      id="filter-yearly"  style="border-radius:0 50px 50px 0;padding:6px 18px;font-weight:600;font-size:0.7rem;border-color:#ddd;color:#888;">Yearly</button>
        </div>
    </div>
    @php
        $days   = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $values = [0,0,0,0,0,0,0];
        $maxVal = max($values) > 0 ? max($values) : 1;
    @endphp
    <div class="chart-container">
        @foreach($days as $index => $day)
            <div class="chart-bar-wrapper">
                <div class="chart-bar" style="height:{{ ($values[$index]/$maxVal)*120+4 }}px;"></div>
                <div class="chart-bar-label">{{ $day }}</div>
            </div>
        @endforeach
    </div>
    @if(array_sum($values) == 0)
        <div class="chart-empty mt-3">
            <i class="fas fa-chart-simple"></i>
            <p>No activity recorded for this period</p>
        </div>
    @endif
    <div class="text-center small text-muted mt-2" style="font-size:0.65rem;letter-spacing:0.5px;">Your booking trends will appear here</div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4" style="border:1px solid #fce4ec;">
            <div class="modal-header" style="border-color:#fce4ec;">
                <h5 class="modal-title fw-bold" style="color:#333;">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="color:#888;font-size:0.88rem;">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2">Reason for Cancellation *</label>
                    <textarea name="cancellation_reason" rows="3" class="form-control" required placeholder="Please tell us why you're cancelling..."
                              style="border:2px solid #fce4ec;border-radius:10px;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'"></textarea>
                </div>
                <div class="modal-footer" style="border-color:#fce4ec;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Keep Appointment</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background:#ef4444;color:#fff;border:none;font-weight:600;">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('#filter-weekly, #filter-monthly, #filter-yearly').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#filter-weekly, #filter-monthly, #filter-yearly').forEach(b => {
            b.classList.remove('active');
            b.style.background = '';
            b.style.color = '';
            b.classList.add('btn-outline-secondary');
        });
        this.classList.remove('btn-outline-secondary');
        this.classList.add('active');
        this.style.background = '#E91E8C';
        this.style.color = '#fff';
    });
});
function cancelModal(id) {
    document.getElementById('cancelForm').action = `/client/appointments/${id}/cancel`;
    new bootstrap.Modal(document.getElementById('cancelModal')).show();
}
</script>
@endpush