<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerManagementController extends Controller
{
    public function index(Request $request)
    {
        $owners = User::where('role', 'owner')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->withCount('salons')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.owners.index', compact('owners'));
    }

    public function show($id)
    {
        $owner = User::with('salons')->findOrFail($id);
        return view('admin.owners.show', compact('owner'));
    }

    public function toggleStatus($id)
    {
        $owner = User::findOrFail($id);
        $owner->is_active = !$owner->is_active;
        $owner->save();

        return back()->with('success', 'Owner status updated!');
    }
}