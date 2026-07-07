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
    // ===================== Helper: Salon fetch karna =====================
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
 
            // ✅ salon_id filter laga diya
            $services = Service::with('category')
                ->where('salon_id', $salon->id)
                ->orderBy('name')
                ->get();
 
            $services = $services->map(function ($service) {
                $service->bookings = Appointment::where('service_id', $service->id)->count();
                return $service;
            });
 
            $stats = [
                'total_services'    => $services->count(),
                'active_services'   => $services->where('is_active', true)->count(),
                'inactive_services' => $services->where('is_active', false)->count(),
                'avg_duration'      => round($services->avg('duration') ?? 0),
            ];
 
            return view('owner.services.index', compact('stats', 'services', 'salon'));
 
        } catch (\Exception $e) {
            \Log::error('Service Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load services.');
        }
    }
 
    public function create()
    {
        try {
            $salon = $this->getOwnerSalon();
 
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }
 
            $categories = Category::where('salon_id', $salon->id)->orderBy('name')->get();
 
            if ($categories->isEmpty()) {
                $defaultCategories = ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup', 'Body Treatment'];
                foreach ($defaultCategories as $catName) {
                    Category::create(['salon_id' => $salon->id, 'name' => $catName, 'is_active' => true]);
                }
                $categories = Category::where('salon_id', $salon->id)->orderBy('name')->get();
            }
 
            return view('owner.services.create', compact('categories', 'salon'));
 
        } catch (\Exception $e) {
            \Log::error('Service Create Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load create page.');
        }
    }
 
    public function store(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
 
            if (!$salon) {
                return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
            }
 
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:255',
                'category_id'  => 'required|exists:categories,id',
                'duration'     => 'required|integer|min:1|max:480',
                'price'        => 'required|numeric|min:0|max:999999',
                'description'  => 'nullable|string|max:2000',
                'client_notes' => 'nullable|string|max:500',
                'status'       => 'required|in:Active,Inactive',
                'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
 
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
 
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('service-images', 'public');
            }
 
            // ✅ FIX 1: salon_id null tha, ab sahi set ho raha hai
            $service = Service::create([
                'salon_id'    => $salon->id,          // ← YE FIX HAI
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'duration'    => $request->duration,
                'image'       => $imagePath,
                'is_active'   => $request->status === 'Active',
                'is_package'  => false,
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
 
   public function show($id)
    {
        try {
            $salon = $this->getOwnerSalon();
 
            if (!$salon) {
                return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
            }
 
            $service = Service::with('category')
                ->where('salon_id', $salon->id)
                ->where('id', $id)
                ->first();
 
            if (!$service) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Service not found or does not belong to your salon.');
            }
 
            $bookingsCount = Appointment::where('service_id', $service->id)->count();
 
            $totalRevenue = Appointment::where('service_id', $service->id)
                ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
                ->sum('total_amount');
 
            // ✅ FIX: reviews table mein service_id column check karna
            // Agar column nahi hai to 0 return karo — crash nahi hoga
            $avgRating = 0;
            try {
                $avgRating = \App\Models\Review::where('service_id', $service->id)->avg('rating') ?? 0;
            } catch (\Exception $e) {
                // reviews table mein service_id column nahi hai — 0 rakho
                $avgRating = 0;
            }
 
            $recentBookings = Appointment::where('service_id', $service->id)
                ->with(['client', 'stylist'])
                ->orderBy('appointment_date', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($appointment) {
                    $statusMap = [
                        'pending_payment' => 'Pending',
                        'confirmed'       => 'Confirmed',
                        'completed'       => 'Completed',
                        'cancelled'       => 'Cancelled',
                        'in_progress'     => 'In Progress',
                    ];
                    return [
                        'client'  => optional($appointment->client)->name ?? 'N/A',
                        'date'    => $appointment->appointment_date
                                        ? $appointment->appointment_date->format('M d, Y')
                                        : 'N/A',
                        'stylist' => optional($appointment->stylist)->name ?? 'N/A',
                        'status'  => $statusMap[$appointment->status] ?? ucfirst($appointment->status),
                    ];
                });
 
            $serviceData = [
                'id'             => $service->id,
                'name'           => $service->name,
                'category'       => optional($service->category)->name ?? 'Uncategorized',
                'duration'       => $service->duration,
                'price'          => $service->price,
                'discount_price' => null,
                'bookings'       => $bookingsCount,
                'revenue'        => $totalRevenue,
                'rating'         => round($avgRating, 1),
                'description'    => $service->description ?? 'No description provided.',
                'client_notes'   => $service->client_notes ?? '',
                'status'         => $service->is_active ? 'Active' : 'Inactive',
                'image_url'      => $service->image ? asset('storage/' . $service->image) : null,
            ];
 
            return view('owner.services.show', [
                'service'        => $serviceData,
                'recentBookings' => $recentBookings,
            ]);
 
        } catch (\Exception $e) {
            \Log::error('Service Show Error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->route('owner.services.index')
                ->with('error', 'Unable to load service: ' . $e->getMessage());
        }
    }
 
    public function edit($id)
    {
        try {
            $salon = $this->getOwnerSalon();
 
            if (!$salon) {
                return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
            }
 
            // ✅ Same fix: manual find with salon_id check
            $service = Service::with('category')
                ->where('salon_id', $salon->id)
                ->where('id', $id)
                ->first();
 
            if (!$service) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Service not found.');
            }
 
            $categories = Category::where('salon_id', $salon->id)->orderBy('name')->get();
 
            $serviceData = [
                'id'             => $service->id,
                'name'           => $service->name,
                'category_id'    => $service->category_id,
                'category'       => $service->category->name ?? '',
                'duration'       => $service->duration,
                'price'          => $service->price,
                'discount_price' => null,
                'description'    => $service->description,
                'client_notes'   => $service->client_notes ?? '',
                'status'         => $service->is_active ? 'Active' : 'Inactive',
                'image_url'      => $service->image ? asset('storage/' . $service->image) : null,
            ];
 
            return view('owner.services.edit', compact('serviceData', 'categories', 'salon') + ['service' => $serviceData]);
 
        } catch (\Exception $e) {
            \Log::error('Service Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.services.index')->with('error', 'Service not found.');
        }
    }
 
    public function update(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();
 
            if (!$salon) {
                return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
            }
 
            // ✅ Manual find with salon_id check
            $service = Service::where('salon_id', $salon->id)->where('id', $id)->first();
 
            if (!$service) {
                return redirect()->route('owner.services.index')->with('error', 'Service not found.');
            }
 
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:255',
                'category_id'  => 'required|exists:categories,id',
                'duration'     => 'required|integer|min:1|max:480',
                'price'        => 'required|numeric|min:0|max:999999',
                'description'  => 'nullable|string|max:2000',
                'client_notes' => 'nullable|string|max:500',
                'status'       => 'required|in:Active,Inactive',
                'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
 
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
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
                'category_id'  => $request->category_id,
                'name'         => $request->name,
                'description'  => $request->description,
                'client_notes' => $request->client_notes,
                'price'        => $request->price,
                'duration'     => $request->duration,
                'image'        => $imagePath,
                'is_active'    => $request->status === 'Active',
            ]);
 
            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $service->name . '" updated successfully!');
 
        } catch (\Exception $e) {
            \Log::error('Service Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update service.')->withInput();
        }
    }
 
    public function destroy($id)
    {
        try {
            $salon = $this->getOwnerSalon();
 
            if (!$salon) {
                return redirect()->route('owner.services.index')->with('error', 'Salon not found.');
            }
 
            // ✅ Manual find with salon_id check
            $service = Service::where('salon_id', $salon->id)->where('id', $id)->first();
 
            if (!$service) {
                return redirect()->route('owner.services.index')->with('error', 'Service not found.');
            }
 
            $serviceName = $service->name;
 
            $appointmentCount = Appointment::where('service_id', $service->id)->count();
            if ($appointmentCount > 0) {
                return redirect()->route('owner.services.index')
                    ->with('error', 'Cannot delete "' . $serviceName . '" — it has ' . $appointmentCount . ' appointment(s).');
            }
 
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
 
            $service->delete();
 
            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $serviceName . '" deleted successfully!');
 
        } catch (\Exception $e) {
            \Log::error('Service Delete Error: ' . $e->getMessage());
            return redirect()->route('owner.services.index')->with('error', 'Unable to delete service.');
        }
    }
 
    public function toggleStatus(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();
 
            $service = Service::where('salon_id', $salon->id)->where('id', $id)->firstOrFail();
            $service->is_active = !$service->is_active;
            $service->save();
 
            $status = $service->is_active ? 'Active' : 'Inactive';
 
            return redirect()->route('owner.services.index')
                ->with('success', 'Service "' . $service->name . '" is now ' . $status);
 
        } catch (\Exception $e) {
            \Log::error('Service Toggle Error: ' . $e->getMessage());
            return redirect()->route('owner.services.index')->with('error', 'Unable to toggle service status.');
        }
    }
}