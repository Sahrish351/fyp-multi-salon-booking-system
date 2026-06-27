<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Notifications\SalonSuspendedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SalonManagementController extends Controller
{
    public function index(Request $request)
    {
        $salons = Salon::with('owner')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->city, fn($q) => $q->where('city', $request->city))
            ->when($request->search, fn($q) => $q->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('phone', 'like', '%' . $request->search . '%')
                      ->orWhereHas('owner', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $cities = Salon::distinct()->orderBy('city')->pluck('city')->filter()->values();

        return view('admin.salons.index', compact('salons', 'cities'));
    }

    public function create()
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.salons.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'owner_id'    => 'required|exists:users,id',
            'city'        => 'required|string|max:100',
            'area'        => 'required|string|max:100',
            'address'     => 'required|string',
            'phone'       => 'required|string|max:20',
            'email'       => 'required|email|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('salons', 'public');
        }

        $data['status'] = 'approved';
        $data['slug']   = Str::slug($request->name) . '-' . uniqid();

        Salon::create($data);

        return redirect()->route('admin.salons.index')
            ->with('success', 'Salon created successfully!');
    }

    public function show(Salon $salon)
    {
        $salon->load('owner', 'services', 'stylists', 'reviews', 'appointments');
        return view('admin.salons.show', compact('salon'));
    }

    public function edit($id)
    {
        $salon  = Salon::findOrFail($id);
        $owners = User::where('role', 'owner')->get();
        return view('admin.salons.edit', compact('salon', 'owners'));
    }

    public function update(Request $request, $id)
    {
        $salon = Salon::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'owner_id'    => 'required|exists:users,id',
            'city'        => 'required|string|max:100',
            'area'        => 'required|string|max:100',
            'address'     => 'required|string',
            'phone'       => 'required|string|max:20',
            'email'       => 'required|email|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
            'status'      => 'in:active,suspended,approved,pending',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($salon->logo) {
                Storage::disk('public')->delete($salon->logo);
            }
            $data['logo'] = $request->file('logo')->store('salons', 'public');
        }

        $salon->update($data);

        return redirect()->route('admin.salons.index')
            ->with('success', 'Salon updated successfully!');
    }

    public function destroy($id)
    {
        $salon = Salon::findOrFail($id);
        if ($salon->logo) {
            Storage::disk('public')->delete($salon->logo);
        }
        $salon->delete();

        return redirect()->route('admin.salons.index')
            ->with('success', 'Salon deleted successfully!');
    }

    public function suspend(Request $request, Salon $salon)
    {
        $request->validate(['reason' => 'required']);
        $salon->update(['status' => 'suspended']);
        $salon->owner->notify(new SalonSuspendedNotification($salon));
        return back()->with('success', 'Salon suspended.');
    }

    public function restore(Salon $salon)
    {
        $salon->update(['status' => 'approved']);
        return back()->with('success', 'Salon restored.');
    }
}