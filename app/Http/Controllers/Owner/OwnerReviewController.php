<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class OwnerReviewController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'avg_rating' => 4.9,
            'total'      => 1248,
            'five_star'  => 1089,
            'this_month' => 87,
        ];
 
        $reviews = $this->dummyReviews();
 
        return view('owner.reviews.index', compact('stats', 'reviews'));
    }
 
   
    public function create()
    {
        return view('owner.reviews.create');
    }
 
   
    public function store(Request $request)
    {
        return redirect()->route('owner.reviews.index');
    }
 
    public function show($review)
    {
        $reviewData = $this->findDummyReview($review);
 
        return view('owner.reviews.show', ['review' => $reviewData]);
    }
 

    public function edit($review)
    {
        $reviewData = $this->findDummyReview($review);
 
        return view('owner.reviews.edit', ['review' => $reviewData]);
    }
 
   
    public function update(Request $request, $review)
    {
        return redirect()->route('owner.reviews.show', ['review' => $review]);
    }
 
    public function destroy(Request $request, $review)
    {
        return redirect()->route('owner.reviews.index')->with('success', 'Review deleted!');
    }
 
   
    public function approve(Request $request, $review)
    {
        return back()->with('success', 'Review approved!');
    }
 
    public function reply(Request $request, $review)
    {
        return back()->with('success', 'Reply posted!');
    }
 
   
    public function toggleFlag(Request $request, $review)
    {
        return back()->with('success', 'Review flagged and hidden from public view.');
    }
 
    private function dummyReviews(): array
    {
        return [
            [
                'id' => 1, 'client_name' => 'Sarah Johnson', 'service' => 'Hair Styling', 'stylist' => 'Emma Wilson',
                'date' => 'Jun 7, 2026', 'rating' => 5,
                'comment' => 'Amazing experience! Emma did an incredible job with my hair. The salon is beautiful and the staff is so professional.',
                'approved' => true, 'flagged' => false, 'owner_reply' => null,
            ],
            [
                'id' => 2, 'client_name' => 'Michael Chen', 'service' => 'Haircut', 'stylist' => 'James Brown',
                'date' => 'Jun 6, 2026', 'rating' => 5,
                'comment' => "Best haircut I've ever had. James really knows what he's doing. Highly recommend!",
                'approved' => true, 'flagged' => false, 'owner_reply' => 'Thank you so much, Michael! We look forward to seeing you again.',
            ],
            [
                'id' => 3, 'client_name' => 'Emily Davis', 'service' => 'Manicure', 'stylist' => 'Sophia Lee',
                'date' => 'Jun 5, 2026', 'rating' => 4,
                'comment' => 'Great service overall, though I had to wait a bit longer than expected. Still very happy with the results.',
                'approved' => false, 'flagged' => false, 'owner_reply' => null,
            ],
            [
                'id' => 4, 'client_name' => 'David Miller', 'service' => 'Facial Treatment', 'stylist' => 'Olivia Martinez',
                'date' => 'Jun 4, 2026', 'rating' => 2,
                'comment' => 'The treatment was rushed and not what I expected for the price.',
                'approved' => false, 'flagged' => true, 'owner_reply' => null,
            ],
        ];
    }
 
   
    private function findDummyReview($id): array
    {
        $reviews = $this->dummyReviews();
 
        foreach ($reviews as $r) {
            if ($r['id'] == $id) {
                return $r;
            }
        }
 
        return $reviews[0];
    }
}
 