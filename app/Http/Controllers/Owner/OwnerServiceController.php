<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerServiceController extends Controller
{
    /**
     * Route: GET /owner/services  -->  name: owner.services.index
     *
     * Services list page (stat cards + search + table).
     *
     * BAAD ME: Database se real services laayen:
     *   $services = Service::where('salon_id', auth()->user()->salon_id)->get();
     *   $stats['total_services'] = $services->count();
     *   $stats['avg_price']      = round($services->avg('price'));
     *   $stats['avg_duration']   = round($services->avg('duration'));
     */
    public function index(Request $request)
    {
        $stats = [
            'total_services' => 48,
            'avg_price'      => 124,
            'avg_duration'   => 78,
        ];

        $services = [
            ['id' => 1, 'name' => 'Premium Haircut',   'category' => 'Hair Styling', 'duration' => 45,  'price' => 85,  'bookings' => 145, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Hair Coloring',     'category' => 'Hair Styling', 'duration' => 90,  'price' => 120, 'bookings' => 98,  'status' => 'Active'],
            ['id' => 3, 'name' => 'Luxury Manicure',   'category' => 'Nail Care',    'duration' => 60,  'price' => 65,  'bookings' => 132, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Luxury Pedicure',   'category' => 'Nail Care',    'duration' => 75,  'price' => 80,  'bookings' => 118, 'status' => 'Active'],
            ['id' => 5, 'name' => 'Gold Facial',       'category' => 'Facial',       'duration' => 90,  'price' => 150, 'bookings' => 76,  'status' => 'Active'],
            ['id' => 6, 'name' => 'Full Body Massage', 'category' => 'Spa',          'duration' => 90,  'price' => 180, 'bookings' => 54,  'status' => 'Active'],
            ['id' => 7, 'name' => 'Bridal Makeup',     'category' => 'Makeup',       'duration' => 180, 'price' => 350, 'bookings' => 24,  'status' => 'Active'],
            ['id' => 8, 'name' => 'Beard Trim',        'category' => 'Hair Styling', 'duration' => 30,  'price' => 45,  'bookings' => 89,  'status' => 'Active'],
        ];

        return view('owner.services.index', compact('stats', 'services'));
    }

    /**
     * Route: GET /owner/services/create  -->  name: owner.services.create
     *
     * Naya service add karne ka page.
     *
     * BAAD ME: Categories dropdown ke liye real categories laayen:
     *   $categories = Category::where('salon_id', auth()->user()->salon_id)->pluck('name');
     */
    public function create()
    {
        $categories = ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup', 'Body Treatment', 'Hair Treatment'];

        return view('owner.services.create', compact('categories'));
    }

    /**
     * Route: POST /owner/services  -->  name: owner.services.store
     *
     * Naya service add karna (Add Service modal submit).
     *
     * BAAD ME:
     *   $request->validate([
     *       'name'     => 'required|string|max:255',
     *       'category' => 'required|string',
     *       'duration' => 'required|integer|min:1',
     *       'price'    => 'required|numeric|min:0',
     *   ]);
     *   Service::create([...$request->validated(), 'salon_id' => auth()->user()->salon_id]);
     */
    public function store(Request $request)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service added successfully!');
    }

    /**
     * Route: GET /owner/services/{service}/edit  -->  name: owner.services.edit
     *
     * Service edit karne ka page (pre-filled form).
     *
     * BAAD ME:
     *   $service = Service::findOrFail($id)->toArray();
     *   $categories = Category::where('salon_id', auth()->user()->salon_id)->pluck('name');
     */
    public function edit($service)
    {
        $categories = ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup', 'Body Treatment', 'Hair Treatment'];

        // Demo data — id ke mutabiq dummy service bana rahe hain (BAAD ME DB se aayega)
        $serviceData = [
            'id'             => $service,
            'name'           => 'Premium Haircut',
            'category'       => 'Hair Styling',
            'duration'       => 45,
            'price'          => 85,
            'discount_price' => null,
            'description'    => 'A precision haircut tailored to your face shape and style preference, finished with a professional blow-dry.',
            'client_notes'   => 'Please arrive 10 minutes early for a consultation.',
            'status'         => 'Active',
            'image_url'      => null,
        ];

        return view('owner.services.edit', ['service' => $serviceData, 'categories' => $categories]);
    }

    /**
     * Route: PUT/PATCH /owner/services/{service}  -->  name: owner.services.update
     *
     * Service update karna (Edit Service modal submit).
     *
     * BAAD ME:
     *   $service = Service::findOrFail($id);
     *   $service->update($request->validated());
     */
    public function update(Request $request, $service)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service updated successfully!');
    }

    /**
     * Route: DELETE /owner/services/{service}  -->  name: owner.services.destroy
     *
     * Service delete karna (Delete confirmation modal submit).
     *
     * BAAD ME:
     *   Service::findOrFail($id)->delete();
     */
    public function destroy(Request $request, $service)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service deleted successfully!');
    }

    /**
     * Route: GET /owner/services/{service}  -->  name: owner.services.show
     *
     * Service ka detail page (image, stats, description, recent bookings).
     *
     * BAAD ME:
     *   $service = Service::findOrFail($id)->toArray();
     *   $recentBookings = Appointment::where('service_id', $id)
     *       ->latest()->take(5)->get();
     */
    public function show($service)
    {
        $serviceData = [
            'id'             => $service,
            'name'           => 'Premium Haircut',
            'category'       => 'Hair Styling',
            'duration'       => 45,
            'price'          => 85,
            'discount_price' => null,
            'bookings'       => 145,
            'revenue'        => 12325,
            'rating'         => 4.8,
            'description'    => 'A precision haircut tailored to your face shape and style preference, finished with a professional blow-dry.',
            'client_notes'   => 'Please arrive 10 minutes early for a consultation.',
            'status'         => 'Active',
            'image_url'      => null,
        ];

        $recentBookings = [
            ['client' => 'Sarah Johnson', 'date' => 'Jun 8, 2026', 'stylist' => 'Emma Wilson', 'status' => 'Confirmed'],
            ['client' => 'Michael Chen',  'date' => 'Jun 7, 2026', 'stylist' => 'James Brown', 'status' => 'Completed'],
            ['client' => 'Amanda Lee',    'date' => 'Jun 5, 2026', 'stylist' => 'Emma Wilson', 'status' => 'Completed'],
        ];

        return view('owner.services.show', ['service' => $serviceData, 'recentBookings' => $recentBookings]);
    }

    /**
     * Route: POST /owner/services/{service}/toggle-status  -->  name: owner.services.toggle-status
     *
     * Service ko Active/Inactive toggle karna.
     *
     * BAAD ME:
     *   $service = Service::findOrFail($id);
     *   $service->update(['status' => $service->status === 'Active' ? 'Inactive' : 'Active']);
     */
    public function toggleStatus(Request $request, $service)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service status updated!');
    }

    /*
     * NOTE: Categories ke methods (categories, storeCategory, updateCategory,
     * destroyCategory) yahan se hata diye gaye hain. Ab Categories ka apna
     * alag controller hai: App\Http\Controllers\Owner\OwnerCategoryController.
     * Apni routes/web.php mein bhi Categories ki routes ab is naye controller
     * ko point karni hain (dekhen ADD_THESE_ROUTES_TO_web.php.txt - updated version).
     */
}
