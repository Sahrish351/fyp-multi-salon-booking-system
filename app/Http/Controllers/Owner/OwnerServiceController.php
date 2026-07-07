<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Service;
use App\Models\Category;
use App\Models\Salon;
use App\Models\Appointment;

class OwnerServiceController extends Controller
{
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

            $services = Service::where('salon_id', $salonId)
                ->with('category')
                ->orderBy('name')
                ->get();

            $totalServices = $services->count();
            $activeServices = $services->where('is_active', true)->count();
            $inactiveServices = $services->where('is_active', false)->count();
            $avgDuration = round($services->avg('duration') ?? 0);

            $servicesWithBookings = $services->map(function ($service) {
                $bookingsCount = Appointment::where('service_id', $service->id)->count();
                $service->bookings = $bookingsCount;
                return $service;
            });

            $stats = [
                'total_services' => $totalServices,
                'active_services' => $activeServices,
                'inactive_services' => $inactiveServices,
                'avg_duration' => $avgDuration,
            ];

            return view('owner.services.index', [
                'stats' => $stats,
                'services' => $servicesWithBookings,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Service Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load services.');
        }
    }

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

            $categories = Category::where('salon_id', $salon->id)
                ->orderBy('name')
                ->get();

            if ($categories->isEmpty()) {
                $defaultCategories = ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup', 'Body Treatment'];
                foreach ($defaultCategories as $catName) {
                    Category::create([
                        'salon_id' => $salon->id,
                        'name' => $catName,
                        'is_active' => true,
                    ]);
                }
                $categories = Category::where('salon_id', $salon->id)->orderBy('name')->get();
            }

            return view('owner.services.create', [
                'categories' => $categories,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Service Create Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to load create page: ' . $e->getMessage());
        }
    }

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
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'duration' => 'required|integer|min:1|max:480',
                'price' => 'required|numeric|min:0|max:999999',
                'description' => 'nullable|string|max:2000',
                'client_notes' => 'nullable|string|max:500',
                'status' => 'required|in:Active,Inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('service-images', 'public');
            }

            $service = Service::create([
                'salon_id' => $salon->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration' => $request->duration,
                'image' => $imagePath,
                'is_active' => $request->status === 'Active' ? true : false,
                'is_package' => false,
            ]);

            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $service->name . '" created successfully!');

        } catch (\Exception $e) {
            \Log::error('Service Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to create service: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)  // ✅ $id use karein (service nahi)
{
    try {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        // ✅ Direct find
        $service = Service::with('category')->find($id);

        if (!$service) {
            return redirect()->route('owner.services.index')
                ->with('error', 'Service not found.');
        }

        $bookingsCount = Appointment::where('service_id', $service->id)->count();

        $totalRevenue = Appointment::where('service_id', $service->id)
            ->whereHas('payment', function ($query) {
                $query->where('status', 'approved');
            })
            ->sum('total_amount');

        $avgRating = \App\Models\Review::where('service_id', $service->id)
            ->avg('rating') ?? 0;

        $recentBookings = Appointment::where('service_id', $service->id)
            ->with(['client', 'stylist'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($appointment) {
                $statusMap = [
                    'pending_payment' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'in_progress' => 'In Progress',
                ];

                return [
                    'client' => $appointment->client->name ?? 'N/A',
                    'date' => $appointment->appointment_date->format('M d, Y'),
                    'stylist' => $appointment->stylist->name ?? 'N/A',
                    'status' => $statusMap[$appointment->status] ?? ucfirst($appointment->status),
                ];
            });

        $serviceData = [
            'id' => $service->id,
            'name' => $service->name,
            'category' => $service->category->name ?? 'Uncategorized',
            'duration' => $service->duration,
            'price' => $service->price,
            'discount_price' => null,
            'bookings' => $bookingsCount,
            'revenue' => $totalRevenue,
            'rating' => round($avgRating, 1),
            'description' => $service->description ?? 'No description provided.',
            'client_notes' => $service->client_notes ?? '',
            'status' => $service->is_active ? 'Active' : 'Inactive',
            'image_url' => $service->image ? asset('storage/' . $service->image) : null,
        ];

        return view('owner.services.show', [
            'service' => $serviceData,
            'recentBookings' => $recentBookings,
        ]);

    } catch (\Exception $e) {
        \Log::error('Service Show Error: ' . $e->getMessage());
        return redirect()->route('owner.services.index')
            ->with('error', 'Service not found.');
    }
}

    // ✅ FIXED EDIT METHOD - $id use karein
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
                    ->with('error', 'Salon not found.');
            }

            $service = Service::where('salon_id', $salon->id)
                ->find($id);

            if (!$service) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Service not found.');
            }

            $categories = Category::where('salon_id', $salon->id)
                ->orderBy('name')
                ->get();

            $serviceData = [
                'id' => $service->id,
                'name' => $service->name,
                'category_id' => $service->category_id,
                'category' => $service->category->name ?? '',
                'duration' => $service->duration,
                'price' => $service->price,
                'discount_price' => null,
                'description' => $service->description,
                'client_notes' => $service->client_notes ?? '',
                'status' => $service->is_active ? 'Active' : 'Inactive',
                'image_url' => $service->image ? asset('storage/' . $service->image) : null,
            ];

            return view('owner.services.edit', [
                'service' => $serviceData,
                'categories' => $categories,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Service Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.services.index')
                ->with('error', 'Service not found.');
        }
    }

    // ✅ FIXED UPDATE METHOD - $id use karein
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

            $service = Service::where('salon_id', $salon->id)->find($id);

            if (!$service) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Service not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'duration' => 'required|integer|min:1|max:480',
                'price' => 'required|numeric|min:0|max:999999',
                'description' => 'nullable|string|max:2000',
                'client_notes' => 'nullable|string|max:500',
                'status' => 'required|in:Active,Inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($request->hasFile('image')) {
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                }
                $imagePath = $request->file('image')->store('service-images', 'public');
            } else {
                $imagePath = $service->image;
            }

            $service->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration' => $request->duration,
                'image' => $imagePath,
                'is_active' => $request->status === 'Active' ? true : false,
            ]);

            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $service->name . '" updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Service Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update service: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ✅ FIXED DESTROY METHOD - $id use karein
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

            $service = Service::where('salon_id', $salon->id)->find($id);

            if (!$service) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Service not found.');
            }

            $serviceName = $service->name;

            $appointmentCount = Appointment::where('service_id', $service->id)->count();
            if ($appointmentCount > 0) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Cannot delete "' . $serviceName . '" because it has ' . $appointmentCount . ' appointment(s).');
            }

            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $service->delete();

            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $serviceName . '" deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Service Delete Error: ' . $e->getMessage());
            return redirect()->route('owner.services.index')
                ->with('error', 'Unable to delete service: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, $id)
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

            $service = Service::where('salon_id', $salon->id)->find($id);

            if (!$service) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Service not found.');
            }

            $service->is_active = !$service->is_active;
            $service->save();

            $status = $service->is_active ? 'Active' : 'Inactive';

            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $service->name . '" is now ' . $status);

        } catch (\Exception $e) {
            \Log::error('Service Toggle Error: ' . $e->getMessage());
            return redirect()->route('owner.services.index')
                ->with('error', 'Unable to toggle service status.');
        }
    }
}