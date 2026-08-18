<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintSubmitController extends Controller
{
    public function index()
    {
        // Complaints list page load karega
        return view('client.complaints.index');
    }

    public function create()
    {
        // Complaint form show karega
        return view('client.complaints.create');
    }

    public function store(Request $request)
    {
        // Complaint submit karne ka logic
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Yahan aap ka complaint save hone ka code aayega

        return redirect()->route('client.complaints.index')
                         ->with('success', 'Complaint submitted successfully!');
    }
}