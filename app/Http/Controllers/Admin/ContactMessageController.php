<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::query()
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            })
            ->when($request->status === 'read', fn($q) => $q->where('status', 'read'))
            ->when($request->status === 'unread', fn($q) => $q->where('status', 'unread'))
            ->when($request->status === 'replied', fn($q) => $q->where('status', 'replied'))
            ->latest()
            ->paginate(20);

        // ✅ Counts for header stats
        $unreadCount = ContactMessage::where('status', 'unread')->count();
        $readCount = ContactMessage::where('status', 'read')->count();
        $repliedCount = ContactMessage::where('status', 'replied')->count();
        $totalCount = ContactMessage::count();

        return view('admin.contact-messages.index', compact('messages', 'unreadCount', 'readCount', 'repliedCount', 'totalCount'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // ✅ Mark as read if unread
        if ($message->status === 'unread') {
            $message->update([
                'status' => 'read',
                'read_at' => now(),
                'read_by' => auth()->id(),
            ]);
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update([
            'status' => 'read',
            'read_at' => now(),
            'read_by' => auth()->id(),
        ]);

        return back()->with('success', 'Message marked as read.');
    }

    public function markAsUnread($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update([
            'status' => 'unread',
            'read_at' => null,
            'read_by' => null,
        ]);

        return back()->with('success', 'Message marked as unread.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:10',
        ]);

        $message = ContactMessage::findOrFail($id);

        $message->update([
            'reply' => $request->reply,
            'status' => 'replied',
            'replied_at' => now(),
            'replied_by' => auth()->id(),
        ]);

        
        Mail::to($message->email)->send(new ContactReplyMail($message));

        
        $matchedUser = User::where('email', $message->email)->first();

        if ($matchedUser) {
            NotificationHelper::sendToUser(
                $matchedUser->id,
                $matchedUser->salon_id ?? 0,
                'contact_message_reply',
                [
                    'title' => 'Aapke message ka jawab aaya hai',
                    'message' => Str::limit($request->reply, 100),
                    'link' => null, // yahan client ka "my messages" route daal dena agar hai
                ]
            );
        }

        return back()->with('success', 'Reply sent to ' . $message->email);
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        ContactMessage::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Messages deleted successfully.');
    }
}