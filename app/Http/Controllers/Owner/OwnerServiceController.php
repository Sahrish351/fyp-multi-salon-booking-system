<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerServiceController extends Controller
{
    
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

   
    public function create()
    {
        $categories = ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup', 'Body Treatment', 'Hair Treatment'];

        return view('owner.services.create', compact('categories'));
    }

  
    public function store(Request $request)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service added successfully!');
    }

    public function edit($service)
    {
        $categories = ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup', 'Body Treatment', 'Hair Treatment'];

      
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

    public function update(Request $request, $service)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service updated successfully!');
    }


    public function destroy(Request $request, $service)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service deleted successfully!');
    }

 
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

   
    public function toggleStatus(Request $request, $service)
    {
        return redirect()->route('owner.services.index')->with('success', 'Service status updated!');
    }

    
    public function categories(Request $request)
    {
        $categories = [
            ['id' => 1, 'name' => 'Hair Styling',     'count' => 12, 'icon_bg' => 'cat-gold'],
            ['id' => 2, 'name' => 'Nail Care',        'count' => 8,  'icon_bg' => 'cat-purple'],
            ['id' => 3, 'name' => 'Facial Treatment', 'count' => 6,  'icon_bg' => 'cat-green'],
            ['id' => 4, 'name' => 'Spa & Massage',    'count' => 5,  'icon_bg' => 'cat-blue'],
            ['id' => 5, 'name' => 'Makeup',           'count' => 7,  'icon_bg' => 'cat-orange'],
            ['id' => 6, 'name' => 'Body Treatment',   'count' => 4,  'icon_bg' => 'cat-pink'],
            ['id' => 7, 'name' => 'Hair Treatment',   'count' => 6,  'icon_bg' => 'cat-teal'],
        ];

        return view('owner.categories', compact('categories'));
    }

   
    public function storeCategory(Request $request)
    {
        return redirect()->route('owner.categories.index')->with('success', 'Category added successfully!');
    }

   
    public function updateCategory(Request $request, $category)
    {
        return redirect()->route('owner.categories.index')->with('success', 'Category updated successfully!');
    }

    
    public function destroyCategory(Request $request, $category)
    {
        return redirect()->route('owner.categories.index')->with('success', 'Category deleted successfully!');
    }
}
