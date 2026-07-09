<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['client', 'salon', 'appointment.service'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('client', fn($c) => $c->where('name', 'like', '%'.$request->search.'%'))
                  ->orWhereHas('salon',  fn($s) => $s->where('name', 'like', '%'.$request->search.'%'))
                  ->orWhere('comment',   'like', '%'.$request->search.'%');
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'published') {
                $query->where('is_approved', true)->where('is_flagged', false);
            } elseif ($request->status === 'reported') {
                $query->where('is_flagged', true);
            } elseif ($request->status === 'hidden') {
                $query->where('is_approved', false)->where('is_flagged', false);
            }
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Summary counts
        $counts = [
            'total'     => Review::count(),
            'published' => Review::where('is_approved', true)->where('is_flagged', false)->count(),
            'reported'  => Review::where('is_flagged', true)->count(),
            'hidden'    => Review::where('is_approved', false)->where('is_flagged', false)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'counts'));
    }

    public function show(Review $review)
    {
        $review->load(['client', 'salon', 'appointment.service']);
        return view('admin.reviews.show', compact('review'));
    }

    public function hide(Review $review)
    {
        $review->update([
            'is_approved' => false,
            'is_flagged'  => false,
        ]);

        // ✅ Notification – simple message (ya hata sakti hain)
        try {
            $review->client->notify(new \App\Notifications\AppointmentUpdateNotification(
                $review->appointment,
                'review_hidden',
                'Your review for "' . $review->salon->name . '" has been hidden by admin.'
            ));
        } catch (\Exception $e) {
            Log::warning('Hide notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Review hidden successfully.');
    }

    public function publish(Review $review)
    {
        $review->update([
            'is_approved' => true,
            'is_flagged'  => false,
        ]);

        return back()->with('success', 'Review published successfully.');
    }

    public function destroy(Review $review)
    {
        try {
            $review->client->notify(new \App\Notifications\AppointmentUpdateNotification(
                $review->appointment,
                'review_deleted',
                'Your review for "' . $review->salon->name . '" has been removed by admin.'
            ));
        } catch (\Exception $e) {
            Log::warning('Delete notification failed: ' . $e->getMessage());
        }

        // Notify owner
        try {
            $owner = User::find($review->salon->owner_id);
            if ($owner) {
                $owner->notify(new \App\Notifications\AppointmentUpdateNotification(
                    $review->appointment,
                    'review_deleted',
                    'The reported review for "' . $review->salon->name . '" has been removed by admin.'
                ));
            }
        } catch (\Exception $e) {
            Log::warning('Owner delete notification failed: ' . $e->getMessage());
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}