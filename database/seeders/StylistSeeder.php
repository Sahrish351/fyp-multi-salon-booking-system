<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stylist;
use App\Models\Salon;
use App\Models\Service;

class StylistSeeder extends Seeder
{
    public function run()
    {
        $salons = Salon::all();

        if ($salons->isEmpty()) {
            $this->command->error('Pehle Salons create karein!');
            return;
        }

        // 6 Ladies Stylists ka Data
        $stylistTemplates = [
            ['name' => 'Ayesha Khan',    'phone' => '03001234561', 'specializations' => 'Hair Styling & Cutting', 'rating' => 4.9],
            ['name' => 'Sania Mirza',    'phone' => '03001234562', 'specializations' => 'Bridal & Party Makeup', 'rating' => 5.0],
            ['name' => 'Fatima Ali',     'phone' => '03001234563', 'specializations' => 'Hydra Facial & Skin Care', 'rating' => 4.9],
            ['name' => 'Zainab Ahmed',   'phone' => '03001234564', 'specializations' => 'Hair Color & Highlights', 'rating' => 4.8],
            ['name' => 'Maryam Hassan',  'phone' => '03001234565', 'specializations' => 'Keratin & Rebonding', 'rating' => 4.7],
            ['name' => 'Sadaf Noman',   'phone' => '03001234566', 'specializations' => 'Manicure & Pedicure', 'rating' => 4.6],
        ];

        // Har salon ke andar 6 ladies stylists create honge
        foreach ($salons as $salon) {
            foreach ($stylistTemplates as $data) {
                $stylist = Stylist::create([
                    'salon_id'        => $salon->id,
                    'name'            => $data['name'],
                    'phone'           => $data['phone'],
                    'specializations' => $data['specializations'],
                    'rating'          => $data['rating'],
                    'status'          => 'active',
                ]);

                // Salon ki services link karne ke liye
                $salonServices = Service::where('salon_id', $salon->id)->pluck('id')->toArray();
                if (!empty($salonServices) && method_exists($stylist, 'services')) {
                    $stylist->services()->sync($salonServices);
                }
            }
        }
    }
}