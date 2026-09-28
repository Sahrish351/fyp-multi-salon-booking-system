<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Stylist;
use Illuminate\Database\Seeder;

class StylistServiceSeeder extends Seeder
{
    public function run(): void
    {
        $perStylist = 5;

        
        $groups = [
            [
                'triggers' => ['nail', 'manicure', 'pedicure'],
                'words'    => ['nail', 'manicure', 'pedicure'],
            ],
            [
                'triggers' => ['facial', 'skin', 'hydra'],
                'words'    => ['facial', 'skin', 'clean', 'glow', 'hydra', 'acne'],
            ],
            [
                'triggers' => ['makeup', 'bridal'],
                'words'    => ['makeup', 'bridal', 'party', 'airbrush'],
            ],
            [
                'triggers' => ['spa', 'massage'],
                'words'    => ['spa', 'massage', 'scrub', 'aroma'],
            ],
            [
                'triggers' => ['hair', 'keratin', 'rebond', 'color', 'colour', 'cut'],
                'words'    => ['hair', 'keratin', 'rebond', 'cut', 'colour', 'color', 'blow', 'style', 'treatment', 'highlight'],
            ],
        ];

        $done = 0;

        
        $stylists = Stylist::where('salon_id', '!=', 1)->get();

        foreach ($stylists as $stylist) {

           
            $current = $stylist->services()->count();
            if ($current >= 1 && $current <= $perStylist) {
                continue;
            }

            $services = Service::where('salon_id', $stylist->salon_id)->get();
            if ($services->isEmpty()) {
                continue;
            }

            $spec = strtolower((string) $stylist->specializations);

            
            $words = [];
            foreach ($groups as $group) {
                foreach ($group['triggers'] as $trigger) {
                    if (str_contains($spec, $trigger)) {
                        $words = $group['words'];
                        break 2;
                    }
                }
            }

           
            $matched = $services->filter(function ($service) use ($words) {
                $name = strtolower($service->name);
                foreach ($words as $w) {
                    if (str_contains($name, $w)) {
                        return true;
                    }
                }
                return false;
            })->shuffle()->take($perStylist);

            
            if ($matched->count() < $perStylist) {
                $extra = $services->whereNotIn('id', $matched->pluck('id'))
                    ->shuffle()
                    ->take($perStylist - $matched->count());
                $matched = $matched->concat($extra);
            }

            $stylist->services()->sync($matched->pluck('id')->all());
            $done++;
        }

        $this->command->info("Services assign ho gayi: {$done} stylists ko.");
    }
}