<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // ✅ FIXED: guard against a missing/expired session. Without this,
        // hitting this route while logged out (or under the wrong guard)
        // crashes with "Call to a member function notifications() on null"
        // instead of sending the person to log in.
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view notifications.');
        }

        $notifications = $user->notifications()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view notifications.');
        }

        $notification = $user->notifications()->findOrFail($id);

        // Mark as read the moment it's opened, so the bell/list badge
        // updates automatically without needing a separate click.
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', compact('notification'));
    }

    public function sendToAll(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new CustomNotification($request->title, $request->message));
        }

        return back()->with('success', 'Notification sent to all users successfully.');
    }

    public function sendToOwners(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            $owner->notify(new CustomNotification($request->title, $request->message));
        }

        return back()->with('success', 'Notification sent to all owners successfully.');
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();
        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * ── Reusable helper — call this from ANY other controller to notify
     * every admin at once. This is what wires up the actual notification
     * triggers (new salon request, new complaint, new contact message,
     * salon approved/rejected, etc). See the accompanying guide for
     * exactly where to call this from in each of those controllers.
     *
     * Usage from anywhere else in the app:
     *   app(\App\Http\Controllers\Admin\NotificationController::class)
     *       ->notifyAdmins('New Complaint', 'A client filed a complaint against XYZ Salon.', route('admin.complaints.index'));
     */
    public function notifyAdmins(string $title, string $message, ?string $actionUrl = null): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new CustomNotification($title, $message, $actionUrl));
        }
    }
}