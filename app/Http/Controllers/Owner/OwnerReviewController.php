<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OwnerReviewController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    
    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $statusFilter = $request->get('status', 'all');
            $hasFlagColumn = Schema::hasColumn('reviews', 'is_flagged');
            $hasReplyColumn = Schema::hasColumn('reviews', 'owner_reply');

            $query = Review::where('salon_id', $salon->id)
                ->with(['appointment.service', 'appointment.stylist']);

           
            if ($statusFilter === 'pending') {
                $query->where('is_approved', 0);
                if ($hasFlagColumn) {
                    $query->where('is_flagged', 0);
                }
            } elseif ($statusFilter === 'approved') {
                $query->where('is_approved', 1);
            } elseif ($statusFilter === 'flagged' && $hasFlagColumn) {
                $query->where('is_flagged', 1);
            }

            $reviews = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($review) use ($hasFlagColumn, $hasReplyColumn) {
                    $isApproved = (bool) $review->is_approved;
                    $isFlagged  = $hasFlagColumn ? (bool) $review->is_flagged : false;

                    
                    $clientName = 'N/A';
                    $clientId = $review->client_id ?? $review->user_id;
                    if ($clientId) {
                        $user = User::find($clientId);
                        $clientName = $user ? $user->name : 'N/A';
                    }

                    //  Service name — direct service_id ya appointment se
                    $serviceName = 'N/A';
                    if ($review->service_id) {
                        $service = Service::find($review->service_id);
                        $serviceName = $service ? $service->name : 'N/A';
                    } elseif ($review->appointment && $review->appointment->service) {
                        $serviceName = $review->appointment->service->name;
                    }

                    $ownerReply = $hasReplyColumn ? ($review->owner_reply ?? null) : null;

                    return [
                        'id'          => $review->id,
                        'client_name' => $clientName,
                        'service'     => $serviceName,
                        'date'        => $review->created_at
                                            ? $review->created_at->format('M d, Y')
                                            : 'N/A',
                        'rating'      => $review->rating ?? 0,
                        'comment'     => $review->comment ?? 'No comment provided.',
                        'approved'    => $isApproved,
                        'flagged'     => $isFlagged,
                        'owner_reply' => $ownerReply,
                        'status'      => $isFlagged
                                            ? 'Flagged'
                                            : ($isApproved ? 'Approved' : 'Pending'),
                    ];
                });

            $stats = [
                'avg_rating' => round(
                    Review::where('salon_id', $salon->id)
                        ->where('is_approved', 1)
                        ->avg('rating') ?? 0,
                    1
                ),
                'total'      => Review::where('salon_id', $salon->id)->count(),
                'five_star'  => Review::where('salon_id', $salon->id)
                                    ->where('rating', 5)
                                    ->where('is_approved', 1)
                                    ->count(),
                'this_month' => Review::where('salon_id', $salon->id)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count(),
            ];

            return view('owner.reviews.index', compact('stats', 'reviews'));

        } catch (\Exception $e) {
            Log::error('Review Index Error: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return view('owner.reviews.index', [
                'stats'   => ['avg_rating' => 0, 'total' => 0, 'five_star' => 0, 'this_month' => 0],
                'reviews' => collect([]),
            ])->with('error', 'Unable to load reviews: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $salon = $this->getOwnerSalon();
        $appointments = Appointment::where('salon_id', $salon->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->with(['client', 'service'])
            ->latest()->limit(20)->get();

        return view('owner.reviews.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            $appointment = Appointment::where('salon_id', $salon->id)
                ->findOrFail($request->appointment_id);

            Review::create([
                'salon_id'       => $salon->id,
                'appointment_id' => $appointment->id,
                'client_id'      => $appointment->client_id,
                'user_id'        => $appointment->client_id,
                'service_id'     => $appointment->service_id,
                'rating'         => $request->rating,
                'comment'        => $request->comment,
                'is_approved'    => $request->status === 'approved' ? 1 : 0,
            ]);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to add review: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $salon  = $this->getOwnerSalon();
            $review = Review::where('salon_id', $salon->id)->findOrFail($id);

            $isApproved = (bool) $review->is_approved;
            $isFlagged  = Schema::hasColumn('reviews', 'is_flagged')
                            ? (bool) $review->is_flagged : false;

            $clientId   = $review->client_id ?? $review->user_id;
            $user       = $clientId ? User::find($clientId) : null;
            $clientName = $user ? $user->name : 'N/A';

            $service = $review->service_id ? Service::find($review->service_id) : null;
            $serviceName = $service ? $service->name : 'N/A';

            $reviewData = [
                'id'          => $review->id,
                'client_name' => $clientName,
                'service'     => $serviceName,
                'date'        => $review->created_at ? $review->created_at->format('M d, Y') : 'N/A',
                'rating'      => $review->rating ?? 0,
                'comment'     => $review->comment ?? 'No comment provided.',
                'approved'    => $isApproved,
                'flagged'     => $isFlagged,
                'owner_reply' => Schema::hasColumn('reviews', 'owner_reply')
                                    ? ($review->owner_reply ?? null) : null,
                'status'      => $isFlagged ? 'Flagged' : ($isApproved ? 'Approved' : 'Pending'),
            ];

            return view('owner.reviews.show', ['review' => $reviewData]);

        } catch (\Exception $e) {
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Review not found.');
        }
    }

    public function edit($id)
    {
        try {
            $salon  = $this->getOwnerSalon();
            $review = Review::where('salon_id', $salon->id)->findOrFail($id);

            $isApproved = (bool) $review->is_approved;
            $isFlagged  = Schema::hasColumn('reviews', 'is_flagged')
                            ? (bool) $review->is_flagged : false;

            $clientId   = $review->client_id ?? $review->user_id;
            $user       = $clientId ? User::find($clientId) : null;

            $reviewData = [
                'id'          => $review->id,
                'client_name' => $user ? $user->name : 'N/A',
                'service'     => $review->service_id
                                    ? optional(Service::find($review->service_id))->name ?? 'N/A'
                                    : 'N/A',
                'date'        => $review->created_at ? $review->created_at->format('M d, Y') : 'N/A',
                'rating'      => $review->rating ?? 0,
                'comment'     => $review->comment ?? '',
                'owner_reply' => Schema::hasColumn('reviews', 'owner_reply')
                                    ? ($review->owner_reply ?? '') : '',
                'is_approved' => $isApproved,
                'is_flagged'  => $isFlagged,
                'is_pending'  => !$isApproved && !$isFlagged,
            ];

            return view('owner.reviews.edit', ['review' => $reviewData]);

        } catch (\Exception $e) {
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Review not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $salon  = $this->getOwnerSalon();
            $review = Review::where('salon_id', $salon->id)->findOrFail($id);

            $updateData = [
                'rating'      => $request->rating,
                'comment'     => $request->comment,
                'is_approved' => $request->status === 'approved' ? 1 : 0,
            ];

            if (Schema::hasColumn('reviews', 'is_flagged')) {
                $updateData['is_flagged'] = $request->status === 'flagged' ? 1 : 0;
            }
            if (Schema::hasColumn('reviews', 'owner_reply') && $request->filled('reply')) {
                $updateData['owner_reply'] = $request->reply;
            }

            $review->update($updateData);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to update review.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $salon = $this->getOwnerSalon();
            Review::where('salon_id', $salon->id)->findOrFail($id)->delete();

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to delete review.');
        }
    }

   
    public function approve(Request $request, $id)
    {
        try {
            $salon  = $this->getOwnerSalon();
            $review = Review::where('salon_id', $salon->id)->findOrFail($id);

            $updateData = ['is_approved' => 1];
            if (Schema::hasColumn('reviews', 'is_flagged')) {
                $updateData['is_flagged'] = 0;
            }
            $review->update($updateData);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Review approved successfully!');

        } catch (\Exception $e) {
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to approve review.');
        }
    }

    public function reply(Request $request, $id)
    {
        try {
            $salon  = $this->getOwnerSalon();
            $review = Review::where('salon_id', $salon->id)->findOrFail($id);

            if (!Schema::hasColumn('reviews', 'owner_reply')) {
                return redirect()->route('owner.reviews.index')
                    ->with('error', 'Please run migration first: php artisan make:migration add_flags_to_reviews_table');
            }

            $request->validate(['reply' => 'required|string|max:1000']);
            $review->update(['owner_reply' => $request->reply]);

            return redirect()->route('owner.reviews.index')
                ->with('success', 'Reply posted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to post reply.');
        }
    }

   
    public function toggleFlag(Request $request, $id)
    {
        try {
            $salon  = $this->getOwnerSalon();
            $review = Review::where('salon_id', $salon->id)->findOrFail($id);

            if (Schema::hasColumn('reviews', 'is_flagged')) {
                $newFlag = !((bool) $review->is_flagged);
                $review->update([
                    'is_flagged'  => $newFlag,
                    'is_approved' => $newFlag ? 0 : $review->is_approved,
                ]);
                $msg = $newFlag ? 'Review flagged!' : 'Review unflagged!';
            } else {
                $review->update(['is_approved' => 0]);
                $msg = 'Review hidden.';
            }

            return redirect()->route('owner.reviews.index')->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->route('owner.reviews.index')
                ->with('error', 'Unable to flag review.');
        }
    }
}
