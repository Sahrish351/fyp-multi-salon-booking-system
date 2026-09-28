<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalonRealLocationSeeder extends Seeder
{
    public function run(): void
    {
        
        $locations = [
            'beauty-blush-elite'        => ['Main Boulevard Gulberg', 'Lahore',      31.517249, 74.344351],
            'aura-beauty-studio'        => ['DHA Phase 5', 'Lahore',                 31.469700, 74.414200],
            'royal-glow-salon'          => ['MM Alam Road', 'Lahore',                31.507400, 74.342800],
            'elegance-hair-beauty'      => ['Gulberg III', 'Lahore',                 31.515000, 74.335000],
            'new-style-studio'          => ['Johar Town', 'Lahore',                  31.469700, 74.272800],
            'urban-nails-spa'           => ['Gulshan-e-Iqbal', 'Karachi',            24.920000, 67.095800],
            'bliss-beauty-bar'          => ['F-7 Markaz', 'Islamabad',               33.718000, 73.056300],
            'the-makeup-loft'           => ['Saddar', 'Rawalpindi',                  33.597500, 73.047900],
            'vogue-beauty-lounge'       => ['Clifton Block 5', 'Karachi',            24.813800, 67.030000],
            'elegance-salon-islamabad'  => ['F-10 Markaz', 'Islamabad',              33.693800, 73.011300],
            'style-studio-rawalpindi'   => ['Saddar', 'Rawalpindi',                  33.599800, 73.045200],
            'glamour-studio-spa'        => ['G-11 Markaz', 'Islamabad',              33.672500, 72.990300],
        ];

        $done = 0;

        foreach ($locations as $slug => [$address, $city, $lat, $lng]) {
            $updated = DB::table('salons')->where('slug', $slug)->update([
                'address'   => $address,
                'city'      => $city,
                'latitude'  => $lat,
                'longitude' => $lng,
            ]);

            if ($updated) {
                $done++;
            } else {
                $this->command->warn("Slug nahi mila: {$slug}");
            }
        }

        $this->command->info("Locations update ho gayi: {$done} salons ki.");
    }
}