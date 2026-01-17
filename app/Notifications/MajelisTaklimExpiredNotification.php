<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MajelisTaklimExpiredNotification extends Notification
{
    use Queueable;

    public $mt;

    public $status; // 'expired' or 'warning'

    public function __construct($mt, $status = 'expired')
    {
        $this->mt = $mt;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'mt_'.$this->status,
            'uuid' => $this->mt->uuid, // Ensure Sktpiagammt has UUID or use ID
            'nama_majelis' => $this->mt->nama_majelis,
            'mendaftar' => $this->mt->mendaftar,
        ];
    }
}
