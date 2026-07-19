<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationHelper
{
    public static function send(int $salonId, string $type, array $data): void
    {
        try {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => $type,
                'notifiable_type' => 'App\\Models\\Salon',
                'notifiable_id' => $salonId,
                'salon_id' => $salonId,
                'title' => $data['title'] ?? 'Notification', 
                'data' => json_encode([
                    'title' => $data['title'] ?? '',
                    'message' => $data['message'] ?? '',
                    'link' => $data['link'] ?? null,
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
            
            \Log::info('Notification sent to salon: ' . $salonId);
            
        } catch (\Exception $e) {
            \Log::error('NotificationHelper Error: ' . $e->getMessage());
        }
    }

   
    public static function sendToUser(int $userId, int $salonId, string $type, array $data): void
    {
        try {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => $type,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $userId,
                'salon_id' => $salonId,
                'title' => $data['title'] ?? 'Notification',
                'data' => json_encode([
                    'title' => $data['title'] ?? '',
                    'message' => $data['message'] ?? '',
                    'link' => $data['link'] ?? null,
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
            
            \Log::info('Notification sent to user: ' . $userId . ' for salon: ' . $salonId);
            
        } catch (\Exception $e) {
            \Log::error('NotificationHelper sendToUser Error: ' . $e->getMessage());
        }
    }

    public static function unreadCount(int $salonId): int
    {
        try {
            return DB::table('notifications')
                ->where('salon_id', $salonId)
                ->whereNull('read_at')
                ->whereNull('deleted_at')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}