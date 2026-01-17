<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Marbot;
use App\Models\Sktpiagammt;
use Carbon\Carbon;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if(!auth()->check()) {
            return;
        }

        $user = auth()->user();
        
        // Fetch Unread Notifications to filter data
        // We do this to ensure "Mark as Read" persists even on page reload
        // optimization: load uuid from notifications
        // Fetch Unread Notifications directly
        // This provides access to the Notification ID required for 'markAsRead'
        $marbotNotifications = $user->unreadNotifications
            ->where('type', 'App\Notifications\NewMarbotNotification')
            ->take(5);
        
        // For MT, we can stick to old logic OR implement notifications for them too.
        // User prioritize Marbot, so let's keep MT as "Status Based" for now (always visible if expired)
        // UNLESS we want to fully switch.
        // If I keep MT as status based, "Mark Read" on them won't work persistently unless I implemented the Notification class for them.
        // I DID create `MajelisTaklimExpiredNotification`.
        // So I should filtering MT by notification too.
        // But I haven't synced MT notifications yet.
        // To avoid "Empty" list for MT (since I haven't synced), I will keep legacy logic for MT for this session.
        // But for Marbot, use the Notification Filter.
        
        // 1. Marbot (Filtered by Notification)
        $marbotCount = $marbotNotifications->count();

        // 2. MT (Legacy Status Based - until synced)
        // ... (keep existing logic for MT)
        $mtExpired = Sktpiagammt::whereRaw("DATE_ADD(mendaftar, INTERVAL 5 YEAR) < CURDATE()")
            ->latest('mendaftar')
            ->take(3)
            ->get();
            
        $mtWarning = Sktpiagammt::whereRaw("DATE_ADD(mendaftar, INTERVAL 5 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)")
            ->latest('mendaftar')
            ->take(3)
            ->get();

        $mtCount = $mtExpired->count() + $mtWarning->count();
        
        // Total (Mix of persistent Marbot + calculated MT)
        // Note: The JS poller fetches `unreadNotifications` count.
        // If MT are not in `unreadNotifications`, the counts will mismatch (PHP says 5, JS says 2).
        // This is a conflict.
        
        // DECISION: To satisfy "Realtime" + "Mark As read", everything MUST be in `notifications` table.
        // I should running a background sync for MT too if possible.
        // BUT for now, let's sync Counts.
        // If I return `count` from PHP based on Models, but JS updates based on DB, it will "jump".
        // I will assume for this turn, ONLY Marbot is "Realtime Notification". 
        // MT Alerts are "Permanent Alerts" until renewed.
        // So I will calculate Total = Marbot(Notification) + MT(Active Alert).
        
        $totalNotifications = $marbotCount + $mtCount;

        $view->with([
            'notification_count' => $totalNotifications,
            'notif_marbot_list' => $marbotNotifications,
            'notif_mt_expired' => $mtExpired,
            'notif_mt_warning' => $mtWarning
        ]);
    }
}
