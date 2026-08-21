<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Appointment;
use App\Models\Salon;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerNotificationEmail;

class ReviewSubmitController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['salon', 'service', 'review'])
            ->where('client_id', Auth::id())
            ->where('status', 'completed')
            ->latest();

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
            'is_approved'    => true, 
        ]);

        // Notification & Email to Salon Owner
        try {
            $client = Auth::user();
            $salon  = Salon::with('owner')->find($appointment->salon_id);

            if ($salon) {
                // 1. Dashboard Notification to Salon Owner
                $ownerId = $salon->owner_id ?? null;
                if ($ownerId) {
                    NotificationHelper::send(
                        $ownerId,
                        'review',
                        [
                            'title'   => '⭐ New Review Received',
                            'message' => "{$client->name} submitted a {$request->rating}-star review.",
                            'link'    => route('owner.reviews.index'),
                        ]
                    );
                }

                // 2. Email Notification to Salon Owner
                $ownerEmail = $salon->owner->email ?? config('mail.from.address');

                if ($ownerEmail) {
                    $emailSubject = "⭐ New Review Posted: " . $request->rating . " Stars";
                    $emailBody    = "Client <strong>{$client->name}</strong> ne aap ke salon par new review post kiya hai.<br><br>" .
                                    "<strong>Rating:</strong> {$request->rating} / 5 Stars<br>" .
                                    "<strong>Comment:</strong> {$request->comment}<br>" .
                                    "<strong>Booking Ref:</strong> {$appointment->booking_ref}";

                    Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
                }
            }

        } catch (\Exception $e) {
            \Log::error('Review Notification/Email Error: ' . $e->getMessage());
        }

        $this->updateSalonRating($appointment->salon);

        return redirect()->route('client.reviews.show', $review->id)
            ->with('success', 'Thank you! Your review has been submitted.');
    }

    public function show(Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);
        $review->load(['salon', 'appointment.service', 'reply']);
        return view('client.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);

        if ($review->reply) {
            return redirect()->route('client.reviews.show', $review->id)
                ->with('error', 'You cannot edit a review that has been replied to.');
        }

        $review->load(['salon', 'appointment.service']);
        return view('client.reviews.edit', compact('review'));
    }

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

        $this->updateSalonRating($review->salon);

        return redirect()->route('client.reviews.show', $review->id)
            ->with('success', 'Your review has been updated successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->client_id !== Auth::id()) abort(403);

        if ($review->reply) {
            return back()->with('error', 'You cannot delete a review that has been replied to.');
        }

        $salon = $review->salon;
        $review->delete();

        $this->updateSalonRating($salon);

        return redirect()->route('client.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    private function updateSalonRating($salon): void
    {
        if (!$salon) return;

        $avg   = Review::where('salon_id', $salon->id)->where('is_approved', true)->avg('rating');
        $total = Review::where('salon_id', $salon->id)->where('is_approved', true)->count();
        
        $salon->update([
            'rating'        => round($avg ?? 0, 2),
            'total_reviews' => $total
        ]);
    }
}