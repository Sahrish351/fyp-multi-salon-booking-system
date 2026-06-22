<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class OwnerProfileController extends Controller
{
   
    public function index(Request $request)
    {
        $salon = [
            'name'        => 'GlowAura Luxury Salon',
            'tagline'     => 'Luxury Beauty & Wellness',
            'phone'       => '+1 (555) 123-4567',
            'email'       => 'contact@glowaura.com',
            'website'     => 'www.glowaura.com',
            'address'     => '123 Luxury Avenue, Beverly Hills, CA 90210',
            'description' => 'GlowAura is a premier luxury salon offering world-class beauty and wellness services. Experience the ultimate in relaxation and transformation.',
            'logo_url'    => null, 
        ];
 
        return view('owner.profile', compact('salon'));
    }
 
    public function update(Request $request)
    {
        return redirect()->route('owner.profile')->with('success', 'Salon profile updated successfully!');
    }
 

    public function uploadPicture(Request $request)
    {
        return redirect()->route('owner.profile')->with('success', 'Logo updated successfully!');
    }
}