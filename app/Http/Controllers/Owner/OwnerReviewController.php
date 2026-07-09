<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OwnerReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            // ✅ GET FILTER
            $statusFilter = $request->get('status', 'all');

            // ✅ REAL REVIEWS FROM DATABASE
            $query = Review::where('salon_id', $salonId)
                ->with(['client', 'appointment.service', 'appointment.stylist']);

            if ($statusFilter === 'pending') {
                $query->where('status', 'pending');
            } elseif ($statusFilter === 'approved') {
                $query->where('status', 'approved');
            } elseif ($statusFilter === 'flagged') {
                $query->where('is_flagged', true);
            }

            $reviews = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'client_name' => $review->client->name ?? 'N/A',
                        'service' => $review->appointment->service->name ?? 'N/A',
                        'stylist' => $review->appointment->stylist->name ?? 'N/A',
                        'date' => $review->created_at ? date('M d, Y', strtotime($review->created_at)) : 'N/A',
                        'rating' => $review->rating ?? 0,
                        'comment' => $review->comment ?? 'No comment provided.',
                        'approved' => $review->status === 'approved',
                        'flagged' => $review->is_flagged ?? false,
                        'owner_reply' => $review->owner_reply,
                        'status' => $review->is_flagged ? 'Flagged' : ($review->status === 'approved' ? 'Approved' : 'Pending'),
                    ];
                });

            // ✅ STATS
            $stats = [
                'avg_rating' => Review::where('salon_id', $salonId)
                    ->where('status', 'approved')
                    ->avg('rating') ?? 0,
                'total' => Review::where('salon_id', $salonId)->count(),
                'five_star' => Review::where('salon_id', $salonId)
                    ->where('rating', 5)
                    ->where('status', 'approved')
                    ->count(),
                'this_month' => Review::where('salon_id', $salonId)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count(),
            ];

            return view('owner.reviews.index', compact('stats', 'reviews'));

        } catch (\Exception $e) {
            Log::error('Review Index Error: ' . $e->getMessage());
            return view('owner.reviews.index', [
                'stats' => ['avg_rating' => 0, 'total' => 0, 'five_star' => 0, 'this_month' => 0],
                'reviews' => collect([])
            ])->with('error', 'Unable to load reviews.');
        }
    }

    /**
     * Show the form for creating a new review (manual entry).
     */
    public function create()
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            // ✅ GET COMPLETED APPOINTMENTS WITHOUT REVIEWS
            $appointments = Appointment::where('salon_id', $salonId)
                ->where('status', 'completed')
                ->whereDoesntHave('review')
                ->with(['client', 'service', 'stylist'])
                ->orderBy('appointment_date', 'desc')
                ->limit(20)
                ->get();

            return view('owner.reviews.create', compact('appointments'));

        } catch (\Exception $e) {
            Log::error('Review Create Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to load create page.');
        }
    }

    /**
     * Store a manually created review.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|exists:appointments,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
                'status' => 'required|in:pending,approved',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $appointment = Appointment::where('salon_id', $salon->id)
                ->find($request->appointment_id);

            if (!$appointment) {
                return redirect()->back()
                    ->with('error', 'Appointment not found.')
                    ->withInput();
            }

            // ✅ CREATE REVIEW
            Review::create([
                'salon_id' => $salon->id,
                'appointment_id' => $appointment->id,
                'client_id' => $appointment->client_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => $request->status,
                'is_flagged' => false,
            ]);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review added successfully!');

        } catch (\Exception $e) {
            Log::error('Review Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to add review.')
                ->withInput();
        }
    }

    /**
     * Display the specified review.
     */
    public function show($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)
                ->with(['client', 'appointment.service', 'appointment.stylist'])
                ->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $reviewData = [
                'id' => $review->id,
                'client_name' => $review->client->name ?? 'N/A',
                'service' => $review->appointment->service->name ?? 'N/A',
                'stylist' => $review->appointment->stylist->name ?? 'N/A',
                'date' => $review->created_at ? date('M d, Y', strtotime($review->created_at)) : 'N/A',
                'rating' => $review->rating ?? 0,
                'comment' => $review->comment ?? 'No comment provided.',
                'status' => $review->is_flagged ? 'Flagged' : ($review->status === 'approved' ? 'Approved' : 'Pending'),
                'owner_reply' => $review->owner_reply,
                'is_flagged' => $review->is_flagged ?? false,
                'is_approved' => $review->status === 'approved',
                'is_pending' => $review->status === 'pending',
            ];

            return view('owner.reviews.show', ['review' => $reviewData]);

        } catch (\Exception $e) {
            Log::error('Review Show Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Review not found.');
        }
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)
                ->with(['client', 'appointment.service'])
                ->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $reviewData = [
                'id' => $review->id,
                'client_name' => $review->client->name ?? 'N/A',
                'service' => $review->appointment->service->name ?? 'N/A',
                'date' => $review->created_at ? date('M d, Y', strtotime($review->created_at)) : 'N/A',
                'rating' => $review->rating ?? 0,
                'comment' => $review->comment ?? 'No comment provided.',
                'status' => $review->is_flagged ? 'Flagged' : ($review->status === 'approved' ? 'Approved' : 'Pending'),
                'owner_reply' => $review->owner_reply,
                'is_flagged' => $review->is_flagged ?? false,
                'is_approved' => $review->status === 'approved',
                'is_pending' => $review->status === 'pending',
            ];

            return view('owner.reviews.edit', ['review' => $reviewData]);

        } catch (\Exception $e) {
            Log::error('Review Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Review not found.');
        }
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
                'status' => 'required|in:pending,approved,flagged',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => $request->status === 'flagged' ? 'pending' : $request->status,
                'is_flagged' => $request->status === 'flagged',
            ]);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review updated successfully!');

        } catch (\Exception $e) {
            Log::error('Review Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update review.')
                ->withInput();
        }
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $review->delete();

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Review Destroy Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to delete review.');
        }
    }

    /**
     * Approve a review.
     */
    public function approve(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $review->update([
                'status' => 'approved',
                'is_flagged' => false,
            ]);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review approved successfully!');

        } catch (\Exception $e) {
            Log::error('Review Approve Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to approve review.');
        }
    }

    /**
     * Reply to a review.
     */
    public function reply(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $validator = Validator::make($request->all(), [
                'reply' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $review->update([
                'owner_reply' => $request->reply,
            ]);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Reply posted successfully!');

        } catch (\Exception $e) {
            Log::error('Review Reply Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to post reply.');
        }
    }

    /**
     * Toggle flag on a review.
     */
    public function toggleFlag(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $review = Review::where('salon_id', $salonId)->find($id);

            if (!$review) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Review not found.');
            }

            $newFlag = !$review->is_flagged;
            $review->update([
                'is_flagged' => $newFlag,
                'status' => $newFlag ? 'pending' : $review->status,
            ]);

            $message = $newFlag ? 'Review flagged successfully!' : 'Review unflagged successfully!';

            return redirect()->route('owner.reviews.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Review Flag Error: ' . $e->getMessage());
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to flag review.');
        }
    }
}