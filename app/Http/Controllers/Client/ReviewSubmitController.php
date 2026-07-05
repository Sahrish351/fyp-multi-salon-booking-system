<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewSubmitController extends Controller
{
    /**
     * GET /client/reviews
     * Display all reviews for the logged-in client.
     */
    public function index(Request $request)
    {
        $query = Review::with(['salon', 'appointment.service'])
            ->where('client_id', Auth::id())
            ->latest();

        // Optional: Filter by status (pending/approved)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_approved', $request->status === 'approved' ? 1 : 0);
        }

        $reviews = $query->paginate(10);

        return view('client.reviews.index', compact('reviews'));
    }

    /**
     * GET /client/reviews/create/{appointment}
     * Shows the "Write a Review" form for a specific completed appointment.
     */
    public function create(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        if (!$appointment->isCompleted()) {
            return redirect()->route('client.appointments.show', $appointment->id)
                ->with('error', 'You can only review completed appointments.');
        }

        if ($appointment->review) {
            return redirect()->route('client.appointments.show', $appointment->id)
                ->with('error', 'You have already reviewed this appointment.');
        }

        return view('client.reviews.create', compact('appointment'));
    }

    /**
     * POST /client/reviews/{appointment}
     * Store a new review.
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Validate request
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        // Verify ownership and status
        if ($appointment->client_id !== Auth::id() || !$appointment->isCompleted()) {
            return back()->with('error', 'You can only review completed appointments.');
        }

        // Check if review already exists
        if ($appointment->review) {
            return back()->with('error', 'You have already reviewed this appointment.');
        }

        // Create review
        $review = Review::create([
            'client_id'      => Auth::id(),
            'salon_id'       => $appointment->salon_id,
            'appointment_id' => $appointment->id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
            'is_approved'    => false, // Admin/owner will approve
        ]);

        // Update salon rating (only if approved reviews exist)
        $salon = $appointment->salon;
        $avgRating = Review::where('salon_id', $salon->id)
            ->where('is_approved', true)
            ->avg('rating');
        
        $salon->update([
            'rating'        => round($avgRating, 2),
            'total_reviews' => Review::where('salon_id', $salon->id)
                ->where('is_approved', true)
                ->count(),
        ]);

        return redirect()->route('client.appointments.show', $appointment->id)
            ->with('success', 'Thank you! Your review has been submitted for approval.');
    }

    /**
     * GET /client/reviews/{review}
     * Show a single review.
     */
    public function show(Review $review)
    {
        if ($review->client_id !== Auth::id()) {
            abort(403);
        }

        $review->load(['salon', 'appointment.service', 'reply']);

        return view('client.reviews.show', compact('review'));
    }
}