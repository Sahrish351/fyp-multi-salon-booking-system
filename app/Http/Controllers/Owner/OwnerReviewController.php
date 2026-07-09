<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Salon;
use App\Models\ReviewReply;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerReviewController extends Controller
{
    /**
     * Display a listing of reviews for the owner's salon.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.profile')
                ->with('error', 'Please create your salon first.');
        }

        $query = Review::with(['client', 'appointment.service', 'reply'])
            ->where('salon_id', $salon->id)
            ->latest();

        // Filter by status (new/replied)
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'replied') {
                $query->whereHas('reply');
            } elseif ($request->status === 'new') {
                $query->whereDoesntHave('reply');
            }
        }

        $reviews = $query->paginate(15);

        // Stats
        $stats = [
            'total'    => Review::where('salon_id', $salon->id)->count(),
            'new'      => Review::where('salon_id', $salon->id)->whereDoesntHave('reply')->count(),
            'replied'  => Review::where('salon_id', $salon->id)->whereHas('reply')->count(),
            'avg_rating' => Review::where('salon_id', $salon->id)->avg('rating') ?? 0,
        ];

        return view('owner.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Show a single review.
     */
    public function show($id)
    {
        $user = Auth::user();
        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.profile')
                ->with('error', 'Please create your salon first.');
        }

        $review = Review::with(['client', 'appointment.service', 'reply.owner'])
            ->where('salon_id', $salon->id)
            ->findOrFail($id);

        return view('owner.reviews.show', compact('review'));
    }

    /**
     * Reply to a review and send notification to client.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:2|max:500',
        ]);

        $user = Auth::user();
        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.profile')
                ->with('error', 'Please create your salon first.');
        }

        $review = Review::where('salon_id', $salon->id)->findOrFail($id);

        // Create or update reply
        $reply = ReviewReply::updateOrCreate(
            ['review_id' => $review->id],
            [
                'owner_id' => $user->id,
                'reply'    => $request->reply,
            ]
        );

        // Send notification to client
        $client = User::find($review->client_id);
        if ($client) {
            $client->notify(new CustomNotification(
                'Owner Replied to Your Review',
                'The owner of "' . $salon->name . '" has replied to your review.',
                route('client.reviews.show', $review->id)
            ));
        }

        return redirect()->route('owner.reviews.show', $review->id)
            ->with('success', 'Reply posted successfully! Notification sent to client.');
    }
}