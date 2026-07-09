<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Salon;
 
class OwnerNotificationController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }
 
    /**
     * Route: GET /owner/notifications --> owner.notifications.index
     */
    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }
 
            $filter = $request->get('filter', 'all'); // all, unread, read
 
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
 
            // Total unread count
            $unreadCount = DB::table('notifications')
                ->where('salon_id', $salon->id)
                ->whereNull('read_at')
                ->whereNull('deleted_at')
                ->count();
 
            $totalCount = DB::table('notifications')
                ->where('salon_id', $salon->id)
                ->whereNull('deleted_at')
                ->count();
 
            // Format karna
            $notifications = $notificationsRaw->map(function ($n) {
                $data = json_decode($n->data, true) ?? [];
 
                // Icon aur color type ke hisab se
                $typeConfig = [
                    'appointment' => ['icon' => 'bi-calendar-check-fill', 'color' => '#4A7FE0', 'bg' => '#E8F0FD'],
                    'payment'     => ['icon' => 'bi-currency-dollar',      'color' => '#2EAE7D', 'bg' => '#E3F7EF'],
                    'review'      => ['icon' => 'bi-star-fill',            'color' => '#D9A441', 'bg' => '#FEF3DC'],
                    'waitlist'    => ['icon' => 'bi-list-task',            'color' => '#9B6FD1', 'bg' => '#F3EDFB'],
                    'client'      => ['icon' => 'bi-person-fill',          'color' => '#E85588', 'bg' => '#FDE0EC'],
                    'system'      => ['icon' => 'bi-gear-fill',            'color' => '#6B7280', 'bg' => '#F3F4F6'],
                ];
 
                $type   = $n->type ?? 'system';
                $config = $typeConfig[$type] ?? $typeConfig['system'];
 
                // Time ago
                $createdAt = \Carbon\Carbon::parse($n->created_at);
                $timeAgo   = $createdAt->diffForHumans();
 
                return [
                    'id'        => $n->id,
                    'type'      => $type,
                    'title'     => $n->title ?? ($data['title'] ?? 'Notification'),
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
 
    /**
     * Route: POST /owner/notifications/{id}/read --> owner.notifications.read
     * Single notification ko read mark karna
     */
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
 
    /**
     * Route: POST /owner/notifications/read-all --> owner.notifications.read-all
     * Sab notifications ko read mark karna
     */
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
 
    /**
     * Route: DELETE /owner/notifications/{id} --> (optional, agar route hai)
     * Single notification delete karna (soft delete)
     */
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
 