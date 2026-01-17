<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Sktpiagammt;

class NotificationController extends Controller
{
    /**
     * Get unread notifications for polling.
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Database Notifications (Marbot)
        $dbNotifications = $user->unreadNotifications->map(function ($n) {
            return [
                'id' => $n->id,
                'data' => $n->data,
                'created_at_ts' => $n->created_at->timestamp, // For sorting
                'time_ago' => $n->created_at->diffForHumans(),
                'type' => $n->data['type'] ?? 'info',
                'is_legacy' => false
            ];
        });

        // 2. Legacy MT Alerts (Expired)
        $mtExpired = Sktpiagammt::whereRaw("DATE_ADD(mendaftar, INTERVAL 5 YEAR) < CURDATE()")
            ->latest('mendaftar')
            ->take(3)
            ->get()
            ->map(function ($mt) {
                return [
                    'id' => 'mt_exp_' . $mt->id,
                    'data' => [
                        'nama_majelis' => $mt->nama_majelis,
                        'uuid' => $mt->uuid // Assuming MT has uuid
                    ],
                    'created_at_ts' => now()->timestamp, // Priority
                    'time_ago' => 'Expired',
                    'type' => 'mt_expired',
                    'is_legacy' => true
                ];
            });

        // 3. Legacy MT Alerts (Warning)
        $mtWarning = Sktpiagammt::whereRaw("DATE_ADD(mendaftar, INTERVAL 5 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)")
            ->latest('mendaftar')
            ->take(3)
            ->get()
            ->map(function ($mt) {
                return [
                    'id' => 'mt_warn_' . $mt->id,
                    'data' => [
                        'nama_majelis' => $mt->nama_majelis,
                        'uuid' => $mt->uuid
                    ],
                    'created_at_ts' => now()->subMinutes(1)->timestamp, // Slightly lower priority
                    'time_ago' => 'Segera',
                    'type' => 'mt_warning',
                    'is_legacy' => true
                ];
            });

        // Merge and Sort
        $notifications = $dbNotifications->concat($mtExpired)->concat($mtWarning)
            ->sortByDesc('created_at_ts')
            ->values();

        $count = $notifications->count();

        return response()->json([
            'count' => $count,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
    }

    /**
     * Mark all as read.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
    /**
     * Mark as read and redirect (Backend approach for cleaner UX).
     */
    public function readAndRedirect(\Illuminate\Http\Request $request, $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            
            // Determine redirect URL
            // 1. Try 'url' query param
            if ($request->has('url')) {
                return redirect($request->input('url'));
            }

            // 2. Logic based on Type
            if (isset($notification->data['type']) && $notification->data['type'] == 'marbot_new') {
                return redirect()->route('marbot.show', $notification->data['uuid']);
            }
        }

        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan atau sudah dibaca.');
    }
}
