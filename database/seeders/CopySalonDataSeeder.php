<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 
class CopySalonDataSeeder extends Seeder
{
    public function run(): void
    {
        
        DB::table('categories')->where('salon_id', '>', 1)->delete();
        DB::table('services')->where('salon_id', '>', 1)->delete();
        DB::table('stylists')->where('salon_id', '>', 1)->delete();
        DB::table('time_slots')->where('salon_id', '>', 1)->delete();
        DB::table('galleries')->where('salon_id', '>', 1)->delete();
 
        // 2. Salon 1 ka master data fetch karein
        // IMPORTANT: sirf ACTIVE (non soft-deleted) rows uthayein, warna deleted
        // records bhi copy ho jate hain kyunke DB::table() raw query soft-delete
        // scope ko ignore kar deti hai (SoftDeletes trait sirf Eloquent Model pe kaam karta hai)
        $categories = DB::table('categories')->where('salon_id', 1)->whereNull('deleted_at')->get();
        $stylists   = DB::table('stylists')->where('salon_id', 1)->whereNull('deleted_at')->get();
        $timeSlots  = DB::table('time_slots')->where('salon_id', 1)->get(); // time_slots mein deleted_at column nahi hai
        $galleries  = DB::table('galleries')->where('salon_id', 1)->whereNull('deleted_at')->get();
        $services   = DB::table('services')->where('salon_id', 1)->whereNull('deleted_at')->get();
 
        $allSalonIds = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
 
        foreach ($allSalonIds as $targetSalonId) {
            try {
                DB::transaction(function () use ($targetSalonId, $categories, $stylists, $timeSlots, $galleries, $services) {
 
                    $categoryMapping = [];
                    $stylistMapping  = [];
 
                    // ---- Categories ----
                    foreach ($categories as $cat) {
                        $catData = (array) $cat;
                        unset($catData['id']);
                        $catData['salon_id'] = $targetSalonId;
 
                        $newCatId = DB::table('categories')->insertGetId($catData);
                        $categoryMapping[$cat->id] = $newCatId;
                    }
 
                    // ---- Services ----
                    foreach ($services as $service) {
                        $serviceData = (array) $service;
                        unset($serviceData['id']);
                        $serviceData['salon_id'] = $targetSalonId;
 
                        if (isset($serviceData['category_id'], $categoryMapping[$serviceData['category_id']])) {
                            $serviceData['category_id'] = $categoryMapping[$serviceData['category_id']];
                        }
 
                        DB::table('services')->insert($serviceData);
                    }
 
                    // ---- Stylists ----
                    // NOTE: agar 'email' ya koi aur column unique hai to usay per-salon unique bnaya
                    foreach ($stylists as $stylist) {
                        $stylistData = (array) $stylist;
                        unset($stylistData['id']);
                        $stylistData['salon_id'] = $targetSalonId;
 
                        if (isset($stylistData['email']) && !empty($stylistData['email'])) {
                            $stylistData['email'] = 'salon' . $targetSalonId . '_' . $stylistData['email'];
                        }
                        if (isset($stylistData['phone']) && !empty($stylistData['phone'])) {
                            // phone bhi unique ho sakta hai, agar zaroorat na ho to yeh line hata dein
                            // $stylistData['phone'] = $stylistData['phone'];
                        }
 
                        $newStylistId = DB::table('stylists')->insertGetId($stylistData);
                        $stylistMapping[$stylist->id] = $newStylistId;
                    }
 
                    // ---- Gallery ----
                    foreach ($galleries as $gallery) {
                        $galleryData = (array) $gallery;
                        unset($galleryData['id']);
                        $galleryData['salon_id'] = $targetSalonId;
 
                        if (isset($galleryData['category_id'], $categoryMapping[$galleryData['category_id']])) {
                            $galleryData['category_id'] = $categoryMapping[$galleryData['category_id']];
                        }
                        if (isset($galleryData['stylist_id'], $stylistMapping[$galleryData['stylist_id']])) {
                            $galleryData['stylist_id'] = $stylistMapping[$galleryData['stylist_id']];
                        }
 
                        DB::table('galleries')->insert($galleryData);
                    }
 
                    // ---- Time Slots ----
                    foreach ($timeSlots as $slot) {
                        $slotData = (array) $slot;
                        unset($slotData['id']);
                        $slotData['salon_id'] = $targetSalonId;
 
                        if (isset($slotData['stylist_id'], $stylistMapping[$slotData['stylist_id']])) {
                            $slotData['stylist_id'] = $stylistMapping[$slotData['stylist_id']];
                        }
 
                        DB::table('time_slots')->insert($slotData);
                    }
                });
 
                $this->command->info("✅ Salon ID {$targetSalonId} - data copied successfully.");
 
            } catch (\Throwable $e) {
                // Yahan exact error print hoga har salon ke liye, aur baqi loop rukega nahi
                $this->command->error("❌ Salon ID {$targetSalonId} FAILED: " . $e->getMessage());
                Log::error("CopySalonDataSeeder failed for salon {$targetSalonId}: " . $e->getMessage());
            }
        }
    }
}