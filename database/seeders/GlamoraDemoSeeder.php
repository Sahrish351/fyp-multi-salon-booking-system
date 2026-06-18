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
 
       
        $stylistPool = [
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
            ['Nida Farooq',     'Hair Stylist'],
            ['Maham Aziz',      'Makeup Artist'],
            ['Rabia Hussain',   'Nail Technician'],
            ['Saba Iqbal',      'Spa Therapist'],
            ['Areeba Shah',     'Skin Specialist'],
            ['Mehak Tariq',     'Senior Hair Stylist'],
            ['Anum Bashir',     'Bridal Makeup Specialist'],
            ['Laiba Saeed',     'Nail Art Expert'],
        ];
 
       
        $salonsData = [
            // ---- Recommended-leaning (high rating, older) ----
            ['name' => 'Glamora Beauty Lounge',  'slug' => 'glamora-beauty-lounge',  'city' => 'Lahore',     'address' => 'Main Boulevard, Gulberg III, Lahore', 'description' => 'Premium unisex salon offering hair, skin, nail and bridal services.',            'rating' => 5.0, 'reviews' => 128, 'created_offset_days' => 60, 'stylists' => array_slice($stylistPool, 0, 10)],
            ['name' => 'Serenity Spa & Salon',   'slug' => 'serenity-spa-salon',     'city' => 'Lahore',     'address' => 'DHA Phase 5, Lahore',                 'description' => 'A tranquil escape offering massage therapy, facials, and full-body spa treatments.', 'rating' => 4.9, 'reviews' => 89,  'created_offset_days' => 58, 'stylists' => array_slice($stylistPool, 2, 6)],
            ['name' => 'Royal Bridal Studio',    'slug' => 'royal-bridal-studio',    'city' => 'Lahore',     'address' => 'MM Alam Road, Lahore',                'description' => 'Specialist bridal studio for HD makeup, mehndi looks, and walima styling.',          'rating' => 4.8, 'reviews' => 234, 'created_offset_days' => 55, 'stylists' => array_slice($stylistPool, 1, 5)],
            ['name' => 'The Hair Lounge',        'slug' => 'the-hair-lounge',        'city' => 'Karachi',    'address' => 'Clifton Block 5, Karachi',            'description' => 'Modern hair studio specializing in cuts, coloring, and keratin treatments.',         'rating' => 4.7, 'reviews' => 156, 'created_offset_days' => 52, 'stylists' => array_slice($stylistPool, 4, 6)],
 
            // ---- New to Glamora (most recently created) ----
            ['name' => 'New Style Studio',       'slug' => 'new-style-studio',       'city' => 'Lahore',     'address' => 'Johar Town, Lahore',                  'description' => 'Fresh, modern studio bringing the latest hair and styling trends to Lahore.',         'rating' => 4.5, 'reviews' => 45,  'created_offset_days' => 3,  'stylists' => array_slice($stylistPool, 6, 5)],
            ['name' => 'Urban Nails & Spa',      'slug' => 'urban-nails-spa',        'city' => 'Karachi',    'address' => 'Gulshan-e-Iqbal, Karachi',            'description' => 'Trendy nail art and spa destination for the urban professional.',                    'rating' => 4.6, 'reviews' => 32,  'created_offset_days' => 2,  'stylists' => array_slice($stylistPool, 3, 4)],
            ['name' => 'Bliss Beauty Bar',       'slug' => 'bliss-beauty-bar',       'city' => 'Islamabad',  'address' => 'F-7 Sector, Islamabad',               'description' => 'Boutique beauty bar offering quick, high-quality grooming services.',                'rating' => 4.4, 'reviews' => 28,  'created_offset_days' => 4,  'stylists' => array_slice($stylistPool, 7, 4)],
            ['name' => 'The Makeup Loft',        'slug' => 'the-makeup-loft',        'city' => 'Rawalpindi', 'address' => 'Saddar, Rawalpindi',                  'description' => 'Specialist makeup loft for parties, engagements, and photoshoots.',                  'rating' => 4.7, 'reviews' => 67,  'created_offset_days' => 1,  'stylists' => array_slice($stylistPool, 8, 4)],
 
            // ---- Trending (high rating + high review count) ----
            ['name' => 'Vogue Beauty Lounge',    'slug' => 'vogue-beauty-lounge',    'city' => 'Karachi',    'address' => 'Clifton, Karachi',                    'description' => 'High-end beauty lounge favoured by Karachi\'s fashion-forward crowd.',                'rating' => 4.8, 'reviews' => 189, 'created_offset_days' => 40, 'stylists' => array_slice($stylistPool, 5, 6)],
            ['name' => 'Elegance Salon',         'slug' => 'elegance-salon',         'city' => 'Islamabad',  'address' => 'F-10 Markaz, Islamabad',              'description' => 'Elegant full-service salon known for bridal and event makeup.',                      'rating' => 4.7, 'reviews' => 156, 'created_offset_days' => 35, 'stylists' => array_slice($stylistPool, 9, 5)],
            ['name' => 'Style Studio',           'slug' => 'style-studio-rwp',       'city' => 'Rawalpindi', 'address' => 'Saddar, Rawalpindi',                  'description' => 'Popular neighbourhood studio offering haircuts, coloring, and grooming.',             'rating' => 4.6, 'reviews' => 102, 'created_offset_days' => 30, 'stylists' => array_slice($stylistPool, 10, 5)],
            ['name' => 'Trending Now Salon',     'slug' => 'trending-now-salon',     'city' => 'Lahore',     'address' => 'Liberty Market, Lahore',              'description' => 'The salon everyone in Lahore is talking about right now.',                            'rating' => 4.9, 'reviews' => 234, 'created_offset_days' => 25, 'stylists' => array_slice($stylistPool, 11, 6)],
        ];
 
        $totalSalons = 0;
        $totalServices = 0;
        $totalStylists = 0;
 
        foreach ($salonsData as $i => $data) {
            $ownerEmail = 'owner' . ($i + 1) . '@glamora.com';
 
            // ── Owner ──
            $owner = User::firstOrCreate(
                ['email' => $ownerEmail],
                [
                    'name'     => $data['name'] . ' Owner',
                    'password' => Hash::make('password'),
                    'role'     => 'owner',
                    'phone'    => '03' . rand(100000000, 999999999),
                ]
            );
 
            // ── Salon ──
            $createdAt = now()->subDays($data['created_offset_days']);
 
            $salon = Salon::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'owner_id'      => $owner->id,
                    'name'          => $data['name'],
                    'phone'         => '03' . rand(100000000, 999999999),
                    'email'         => Str::slug($data['name']) . '@glamora.com',
                    'city'          => $data['city'],
                    'address'       => $data['address'],
                    'description'   => $data['description'],
                    'open_time'     => '09:00',
                    'close_time'    => '21:00',
                    'status'        => 'approved',
                    'rating'        => $data['rating'],
                    'total_reviews' => $data['reviews'],
                    'created_at'    => $createdAt,
                    'updated_at'    => $createdAt,
                ]
            );
 
            if ($salon->status !== 'approved') {
                $salon->update(['status' => 'approved']);
            }
 
            // ── Services (full 30-service catalog per salon) ──
            foreach ($servicesData as $catName => $services) {
                foreach ($services as [$name, $desc, $duration, $price]) {
                    $created = Service::firstOrCreate(
                        ['salon_id' => $salon->id, 'name' => $name],
                        [
                            'category_id' => $categories[$catName]->id,
                            'description' => $desc,
                            'duration'    => $duration,
                            'price'       => $price,
                            'is_active'   => true,
                        ]
                    );
                    if ($created->wasRecentlyCreated) {
                        $totalServices++;
                    }
                }
            }
 
            // ── Stylists (unique slice per salon) ──
            foreach ($data['stylists'] as [$name, $spec]) {
                $created = Stylist::firstOrCreate(
                    ['salon_id' => $salon->id, 'name' => $name],
                    [
                        'email'           => Str::slug($name . '-' . $salon->slug) . '@glamora.com',
                        'phone'           => '0300' . rand(1000000, 9999999),
                        'specializations' => $spec,
                        'rating'          => round(rand(40, 50) / 10, 1),
                        'is_active'       => true,
                    ]
                );
                if ($created->wasRecentlyCreated) {
                    $totalStylists++;
                }
            }
 
            $totalSalons++;
        }
 
        $this->command->info("Glamora demo data seeded: {$totalSalons} salons, " .
            count($categoriesData) . ' categories, ' .
            "{$totalServices} new services, {$totalStylists} new stylists.");
        $this->command->info('Owner logins: owner1@glamora.com ... owner12@glamora.com / password');
    }
}