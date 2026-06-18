<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\Salon;
use App\Models\User;

class GlamoraDemoSeeder extends Seeder
{
    public function run()
    {
        
        $owner = User::where('role', 'owner')->first();

        if (!$owner) {
            $owner = User::create([
                'name'     => 'Demo Salon Owner',
                'email'    => 'owner@glamora.com',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '03001234567',
            ]);
            $this->command->info('Created owner user: owner@glamora.com / password');
        }

       
        $salon = Salon::firstOrCreate(
            ['owner_id' => $owner->id],
            [
                'name'          => 'Glamora Beauty Lounge',
                'slug'          => 'glamora-beauty-lounge',
                'phone'         => '03001234567',
                'email'         => 'info@glamora.com',
                'city'          => 'Lahore',
                'address'       => 'Main Boulevard, Gulberg III, Lahore',
                'description'   => 'Premium unisex salon offering hair, skin, nail and bridal services.',
                'open_time'     => '09:00',
                'close_time'    => '21:00',
                'status'        => 'approved',
                'rating'        => 4.7,
                'total_reviews' => 128,
            ]
        );

       
        if ($salon->status !== 'approved') {
            $salon->update(['status' => 'approved']);
        }

     
        $categoriesData = [
            ['name' => 'Hair Styling',     'icon' => 'fa-cut'],
            ['name' => 'Hair Coloring',    'icon' => 'fa-palette'],
            ['name' => 'Skin Care',        'icon' => 'fa-spa'],
            ['name' => 'Nail Care',        'icon' => 'fa-hand-sparkles'],
            ['name' => 'Bridal & Makeup',  'icon' => 'fa-gem'],
            ['name' => 'Massage & Spa',    'icon' => 'fa-leaf'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug'      => Str::slug($cat['name']),
                    'icon'      => $cat['icon'],
                    'is_active' => true,
                ]
            );
        }

       
        $servicesData = [
            'Hair Styling' => [
                ['Hair Cut & Trim',          'Precision cut tailored to your face shape',           45, 800],
                ['Blow Dry & Styling',       'Salon-finish blow dry with smoothing brush',           40, 1200],
                ['Hair Straightening',       'Keratin-infused straightening for sleek finish',       90, 3500],
                ['Curls & Waves Styling',    'Long-lasting curls for parties and events',            60, 2000],
                ['Hair Spa Treatment',       'Deep conditioning spa for damaged hair',                75, 2800],
            ],
            'Hair Coloring' => [
                ['Global Hair Color',        'Full head single-tone color application',              90, 4500],
                ['Highlights & Lowlights',   'Dimensional highlights for natural depth',            120, 6000],
                ['Balayage',                 'Hand-painted sun-kissed balayage technique',           150, 8500],
                ['Root Touch-Up',            'Quick root color refresh',                              45, 2200],
                ['Fashion Color (Vivid)',    'Bold fashion colors — pink, blue, purple etc.',        120, 7000],
            ],
            'Skin Care' => [
                ['Classic Facial',           'Deep cleansing facial for glowing skin',                50, 2000],
                ['Gold Facial',               '24K gold facial for radiant skin',                     60, 3500],
                ['Whitening Facial',          'Brightening facial for even skin tone',                55, 3000],
                ['Acne Treatment Facial',     'Targeted treatment for acne-prone skin',                50, 2800],
                ['Anti-Aging Facial',         'Collagen-boost facial reducing fine lines',             65, 4200],
            ],
            'Nail Care' => [
                ['Classic Manicure',          'Shape, cuticle care, polish application',               30, 800],
                ['Gel Manicure',              'Long-lasting chip-free gel polish',                     45, 1500],
                ['Classic Pedicure',          'Foot soak, scrub, polish for soft feet',                40, 1000],
                ['Spa Pedicure',              'Luxury foot spa with massage and mask',                 60, 1800],
                ['Nail Art & Extensions',     'Custom nail art with acrylic extensions',               75, 2500],
            ],
            'Bridal & Makeup' => [
                ['Party Makeup',              'Glam makeup for parties and events',                    60, 3500],
                ['Bridal Makeup (HD)',        'Full HD bridal makeup with trial',                     150, 18000],
                ['Engagement Makeup',         'Soft glam look for engagement ceremony',                90, 9000],
                ['Mehndi Function Makeup',    'Vibrant makeup for mehndi events',                      75, 6000],
                ['Walima Bridal Look',        'Elegant walima bridal makeover',                       150, 16000],
            ],
            'Massage & Spa' => [
                ['Swedish Body Massage',      'Relaxing full body massage therapy',                    60, 3000],
                ['Deep Tissue Massage',       'Therapeutic massage for muscle tension',                75, 3800],
                ['Head & Shoulder Massage',   'Stress-relief massage for head and shoulders',          30, 1500],
                ['Aromatherapy Spa',          'Essential oil aromatherapy full body spa',              90, 4500],
                ['Hot Stone Therapy',         'Heated stone massage for deep relaxation',              80, 5000],
            ],
        ];

        foreach ($servicesData as $catName => $services) {
            foreach ($services as [$name, $desc, $duration, $price]) {
                Service::firstOrCreate(
                    [
                        'salon_id' => $salon->id,
                        'name'     => $name,
                    ],
                    [
                        'category_id' => $categories[$catName]->id,
                        'description' => $desc,
                        'duration'    => $duration,
                        'price'       => $price,
                        'is_active'   => true,
                    ]
                );
            }
        }

      
        $stylistsData = [
            ['Ayesha Malik',    'Hair Styling & Coloring Expert'],
            ['Fatima Noor',     'Bridal Makeup Artist'],
            ['Sana Tariq',      'Skin Care Specialist'],
            ['Hira Khan',       'Nail Art Designer'],
            ['Mahnoor Aslam',   'Hair Coloring & Balayage Expert'],
            ['Zara Sheikh',     'Massage Therapist'],
            ['Amna Riaz',       'Senior Bridal Stylist'],
            ['Sadia Yousaf',    'Facial & Skin Treatment Expert'],
            ['Komal Fareed',    'All-Rounder Hair & Makeup Artist'],
            ['Iqra Saeed',      'Spa & Wellness Specialist'],
        ];

        foreach ($stylistsData as [$name, $spec]) {
            Stylist::firstOrCreate(
                ['salon_id' => $salon->id, 'name' => $name],
                [
                    'email'           => Str::slug($name) . '@glamora.com',
                    'phone'           => '0300' . rand(1000000, 9999999),
                    'specializations' => $spec,
                    'rating'          => round(rand(40, 50) / 10, 1),
                    'is_active'       => true,
                ]
            );
        }

        $this->command->info('Glamora demo data seeded: ' . count($categoriesData) . ' categories, ' .
            (count($categoriesData) * 5) . ' services, ' . count($stylistsData) . ' stylists, salon status: ' . $salon->status);
    }
}
