{{-- ============================================================ --}}
{{-- FILE: resources/views/client/appointments/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Appointments — Glamora')
@section('content')
 
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;"><i class="fas fa-calendar-check me-2" style="color:#E91E8C;"></i>My Appointments</h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">All your salon bookings in one place</p>
    </div>
    <a href="{{ route('salons.index') }}" class="btn btn-sm rounded-pill px-4" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
        <i class="fas fa-plus me-1"></i>New Booking
    </a>
</div>
 
{{-- Status Tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach(['all'=>'All','confirmed'=>'Confirmed','pending_payment'=>'Pending','completed'=>'Completed','cancelled'=>'Cancelled'] as $val=>$lbl)
    <a href="{{ route('client.appointments.index',['status'=>$val]) }}"
       class="btn btn-sm rounded-pill"
       style="{{ request('status')===$val || (!request('status') && $val==='all') ? 'background:#E91E8C;color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
        {{ $lbl }}
    </a>
    @endforeach
</div>
 
<div class="row g-4">
    @forelse($appointments as $appt)
    <div class="col-12">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;transition:all .3s;" onmouseover="this.style.borderColor='#E91E8C';this.style.boxShadow='0 8px 25px rgba(233,30,140,0.1)'" onmouseout="this.style.borderColor='#fce4ec';this.style.boxShadow='none'">
            <div class="row align-items-center g-3">
 
                {{-- Date Badge --}}
                <div class="col-auto">
                    <div class="text-center rounded-3 p-3" style="background:linear-gradient(135deg,#E91E8C,#c2185b);min-width:64px;">
                        <div style="color:#fff;font-size:1.4rem;font-weight:700;line-height:1;">{{ $appt->appointment_date->format('d') }}</div>
                        <div style="color:rgba(255,255,255,0.8);font-size:0.7rem;text-transform:uppercase;">{{ $appt->appointment_date->format('M') }}</div>
                        <div style="color:rgba(255,255,255,0.7);font-size:0.68rem;">{{ $appt->appointment_date->format('Y') }}</div>
                    </div>
                </div>
 
                {{-- Salon & Service Info --}}
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <img src="{{ $appt->salon->logo_url }}" class="rounded-2" width="32" height="32" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($appt->salon->name) }}&background=E91E8C&color=fff'">
                        <h6 class="fw-bold mb-0" style="color:#333;font-size:0.9rem;">{{ $appt->salon->name }}</h6>
                    </div>
                    <div style="color:#888;font-size:0.82rem;"><i class="fas fa-spa me-1" style="color:#E91E8C;font-size:0.75rem;"></i>{{ $appt->service->name }}</div>
                    <div style="color:#888;font-size:0.82rem;"><i class="fas fa-user-circle me-1" style="color:#C9A96E;font-size:0.75rem;"></i>{{ $appt->stylist->name }}</div>
                </div>
 
                {{-- Time & Amount --}}
                <div class="col-md-3">
                    <div style="color:#333;font-size:0.88rem;font-weight:500;"><i class="fas fa-clock me-1" style="color:#E91E8C;font-size:0.78rem;"></i>{{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}</div>
                    <div style="color:#E91E8C;font-weight:700;font-size:1rem;margin-top:4px;">Rs.{{ number_format($appt->total_amount) }}</div>
                    <div style="color:#aaa;font-size:0.75rem;font-family:monospace;">{{ $appt->booking_ref }}</div>
                </div>
 
                {{-- Status --}}
                <div class="col-md-2">
                    @php $sc = ['confirmed'=>['#22c55e','Confirmed'],'payment_submitted'=>['#ffc107','Pay Submitted'],'pending_payment'=>['#f97316','Pending Pay'],'completed'=>['#8b5cf6','Completed'],'cancelled'=>['#ef4444','Cancelled']][$appt->status] ?? ['#aaa',ucfirst($appt->status)]; @endphp
                    <span style="background:{{ $sc[0] }}22;color:{{ $sc[0] }};padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;display:inline-block;">{{ $sc[1] }}</span>
                    @if($appt->payment)
                    <div class="mt-1" style="color:#aaa;font-size:0.72rem;">
                        Payment: <span style="color:{{ $appt->payment->isApproved() ? '#22c55e' : ($appt->payment->isPending() ? '#ffc107' : '#ef4444') }}">{{ ucfirst($appt->payment->status) }}</span>
                    </div>
                    @endif
                </div>
 
                {{-- Actions --}}
                <div class="col-md-2 text-end">
                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                        <a href="{{ route('client.appointments.show',$appt->id) }}" class="btn btn-sm" style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;border-radius:8px;font-size:0.78rem;">
                            <i class="fas fa-eye me-1"></i>Details
                        </a>
                        @if(!in_array($appt->status,['cancelled','completed']))
                        <button class="btn btn-sm" style="background:#fff0f7;color:#ef4444;border:1px solid #fce4ec;border-radius:8px;font-size:0.78rem;"
                                onclick="cancelModal({{ $appt->id }})">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        @endif
                        @if($appt->isCompleted() && !$appt->review)
                        <a href="{{ route('client.reviews.create',$appt->id) }}" class="btn btn-sm" style="background:#fff8e1;color:#f59e0b;border:1px solid #fde68a;border-radius:8px;font-size:0.78rem;">
                            <i class="fas fa-star me-1"></i>Review
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-4" style="border:2px dashed #fce4ec;">
            <i class="fas fa-calendar-times fa-4x mb-3" style="color:rgba(233,30,140,0.2);"></i>
            <h5 style="color:#333;">No appointments yet</h5>
            <p style="color:#aaa;">Start your beauty journey by booking your first appointment</p>
            <a href="{{ route('salons.index') }}" class="btn rounded-pill px-5 mt-2" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
                <i class="fas fa-search me-2"></i>Find a Salon
            </a>
        </div>
    </div>
    @endforelse
</div>
 
@if($appointments->hasPages())
<div class="mt-4">{{ $appointments->links() }}</div>
@endif
 
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
@endsection
@push('scripts')
<script>
function cancelModal(id) {
    document.getElementById('cancelForm').action = `/client/appointments/${id}/cancel`;
    new bootstrap.Modal(document.getElementById('cancelModal')).show();
}
</script>
@endpush