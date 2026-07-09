<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewSubmitController extends Controller
{
    // ── GET /client/reviews ─────────────────────────────────────
    // Shows completed appointments with their review status
    public function index(Request $request)
    {
        $query = Appointment::with(['salon', 'service', 'review'])
            ->where('client_id', Auth::id())
            ->where('status', 'completed')
            ->latest();

        // Filter: reviewed / not_reviewed
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'reviewed') {
                $query->whereHas('review');
            } elseif ($request->status === 'not_reviewed') {
                $query->whereDoesntHave('review');
            }
        }

        $appointments = $query->paginate(10);

        return view('client.reviews.index', compact('appointments'));
    }

    // ── GET /client/reviews/create/{appointment} ─────────────────
    public function create(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) abort(403);

        if (!$appointment->isCompleted()) {
            return redirect()->route('client.appointments.show', $appointment->id)
                ->with('error', 'You can only review completed appointments.');
        }

        if ($appointment->review) {
            return redirect()->route('client.reviews.show', $appointment->review->id)
                ->with('error', 'You have already reviewed this appointment.');
        }

        return view('client.reviews.create', compact('appointment'));
    }

    // ── POST /client/reviews/{appointment} ───────────────────────
    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        if ($appointment->client_id !== Auth::id() || !$appointment->isCompleted()) {
            return back()->with('error', 'You can only review completed appointments.');
        }

        if ($appointment->review) {
            return back()->with('error', 'You have already reviewed this appointment.');
        }

        $review = Review::create([
            'client_id'      => Auth::id(),
            'user_id'        => Auth::id(),
            'salon_id'       => $appointment->salon_id,
            'appointment_id' => $appointment->id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
            'is_approved'    => false,
        ]);

        // Notify salon owner
        $salon = $appointment->salon;
        $owner = User::find($salon->owner_id);
        if ($owner) {
            try {
                $owner->notify(new CustomNotification(
                    'New Review Received',
                    'A new review has been submitted for "' . $salon->name . '" by ' . Auth::user()->name . '.',
                    route('owner.reviews.index')
                ));
            } catch (\Exception $e) {
                \Log::warning('Review notification failed: ' . $e->getMessage());
            }
        }

        // Update salon rating
        $this->updateSalonRating($salon);

        return redirect()->route('client.reviews.show', $review->id)
            ->with('success', 'Thank you! Your review has been submitted.');
    }

    // ── GET /client/reviews/{review} ─────────────────────────────
    public function show(Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);
        $review->load(['salon', 'appointment.service', 'reply']);
        return view('client.reviews.show', compact('review'));
    }

    // ── GET /client/reviews/{review}/edit ────────────────────────
    public function edit(Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);

        // Cannot edit if owner has replied
        if ($review->reply) {
            return redirect()->route('client.reviews.show', $review->id)
                ->with('error', 'You cannot edit a review that has been replied to.');
        }

        $review->load(['salon', 'appointment.service']);
        return view('client.reviews.edit', compact('review'));
    }

    // ── PUT /client/reviews/{review} ─────────────────────────────
    public function update(Request $request, Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);

        if ($review->reply) {
            return back()->with('error', 'You cannot edit a review that has been replied to.');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        // Recalculate salon rating
        $this->updateSalonRating($review->salon);

        return redirect()->route('client.reviews.show', $review->id)
            ->with('success', 'Your review has been updated successfully.');
    }

    // ── DELETE /client/reviews/{review} ──────────────────────────
    public function destroy(Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);

        if ($review->reply) {
            return back()->with('error', 'You cannot delete a review that has been replied to.');
        }

        $salon = $review->salon;
        $review->delete();

        // Recalculate salon rating
        $this->updateSalonRating($salon);

        return redirect()->route('client.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    // ── Helper: update salon avg rating ──────────────────────────
    private function updateSalonRating($salon): void
    {
        $avg   = Review::where('salon_id', $salon->id)->where('is_approved', true)->avg('rating');
        $total = Review::where('salon_id', $salon->id)->where('is_approved', true)->count();
        $salon->update(['rating' => round($avg ?? 0, 2), 'total_reviews' => $total]);
    }
}