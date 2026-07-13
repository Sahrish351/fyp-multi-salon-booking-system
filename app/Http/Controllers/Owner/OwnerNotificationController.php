<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Salon;
use Carbon\Carbon;

class OwnerNotificationController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $filter = $request->get('filter', 'all');

            $query = DB::table('notifications')
                ->where('salon_id', $salon->id)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc');

            if ($filter === 'unread') {
                $query->whereNull('read_at');
            } elseif ($filter === 'read') {
                $query->whereNotNull('read_at');
            }

            $notificationsRaw = $query->get();

            $unreadCount = DB::table('notifications')
                ->where('salon_id', $salon->id)
                ->whereNull('read_at')
                ->whereNull('deleted_at')
                ->count();

            $totalCount = DB::table('notifications')
                ->where('salon_id', $salon->id)
                ->whereNull('deleted_at')
                ->count();

            // ✅ YAHAN SAB NOTIFICATION TYPES ADD KARO
            $typeConfig = [
                'appointment' => ['icon' => 'bi-calendar-check-fill', 'color' => '#4A7FE0', 'bg' => '#E8F0FD'],
                'payment'     => ['icon' => 'bi-credit-card-fill',    'color' => '#2EAE7D', 'bg' => '#E3F7EF'],
                'payment_approved' => ['icon' => 'bi-check-circle-fill', 'color' => '#2EAE7D', 'bg' => '#E3F7EF'],
                'payment_rejected' => ['icon' => 'bi-x-circle-fill',     'color' => '#E85588', 'bg' => '#FDE0EC'],
                'payment_view' => ['icon' => 'bi-eye-fill',              'color' => '#4A7FE0', 'bg' => '#E8F0FD'],
                'receipt_download' => ['icon' => 'bi-file-pdf-fill',     'color' => '#E85588', 'bg' => '#FDE0EC'],
                'review'      => ['icon' => 'bi-star-fill',          'color' => '#D9A441', 'bg' => '#FEF3DC'],
                'waitlist'    => ['icon' => 'bi-list-task',          'color' => '#9B6FD1', 'bg' => '#F3EDFB'],
                'client'      => ['icon' => 'bi-person-fill',        'color' => '#E85588', 'bg' => '#FDE0EC'],
                'system'      => ['icon' => 'bi-gear-fill',          'color' => '#6B7280', 'bg' => '#F3F4F6'],
            ];

            $notifications = $notificationsRaw->map(function ($n) use ($typeConfig) {
                $data = json_decode($n->data, true) ?? [];

                $type   = $n->type ?? 'system';
                $config = $typeConfig[$type] ?? $typeConfig['system'];

                $createdAt = Carbon::parse($n->created_at);
                $timeAgo   = $createdAt->diffForHumans();

                return [
                    'id'        => $n->id,
                    'type'      => $type,
                    'title'     => $data['title'] ?? $n->title ?? 'Notification',
                    'message'   => $data['message'] ?? '',
                    'link'      => $data['link'] ?? null,
                    'is_read'   => !is_null($n->read_at),
                    'time_ago'  => $timeAgo,
                    'date'      => $createdAt->format('M d, Y h:i A'),
                    'icon'      => $config['icon'],
                    'icon_color'=> $config['color'],
                    'icon_bg'   => $config['bg'],
                ];
            })->toArray();

            return view('owner.notifications.index', compact(
                'notifications',
                'unreadCount',
                'totalCount',
                'filter'
            ));

        } catch (\Exception $e) {
            \Log::error('Notification Index Error: ' . $e->getMessage());
            return view('owner.notifications.index', [
                'notifications' => [],
                'unreadCount'   => 0,
                'totalCount'    => 0,
                'filter'        => 'all',
            ])->with('error', 'Could not load notifications.');
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();

            DB::table('notifications')
                ->where('id', $id)
                ->where('salon_id', $salon->id)
                ->update(['read_at' => now()]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Notification marked as read.');

        } catch (\Exception $e) {
            return back()->with('error', 'Could not update notification.');
        }
    }

    public function markAllRead(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();

            DB::table('notifications')
                ->where('salon_id', $salon->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'All notifications marked as read.');

        } catch (\Exception $e) {
            return back()->with('error', 'Could not update notifications.');
        }
    }

    public function destroy($id)
    {
        try {
            $salon = $this->getOwnerSalon();

            DB::table('notifications')
                ->where('id', $id)
                ->where('salon_id', $salon->id)
                ->update(['deleted_at' => now()]);

            return back()->with('success', 'Notification dismissed.');

        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete notification.');
        }
    }
}