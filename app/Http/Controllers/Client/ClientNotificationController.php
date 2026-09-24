<?php
 
namespace App\Http\Controllers\Client;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class ClientNotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('client.notifications.index', compact('notifications'));
    }
 
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
 
        // View button: notification ke link / action_url par le jao (agar hai), warna wapis
        $url = $notification->data['action_url'] ?? $notification->data['link'] ?? null;
 
        if ($url) {
            return redirect($url);
        }
 
        return back()->with('success', 'Notification marked as read.');
    }
 
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
 
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }
}
 