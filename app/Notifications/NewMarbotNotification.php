<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewMarbotNotification extends Notification
{
    use Queueable;

    public $marbot;

    public function __construct($marbot)
    {
        $this->marbot = $marbot;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'marbot_new',
            'uuid' => $this->marbot->uuid,
            'nama_lengkap' => $this->marbot->nama_lengkap,
            'rumah_ibadah' => $this->marbot->rumah_ibadah ? 
                ($this->marbot->tipe_rumah_ibadah == 'Masjid' ? $this->marbot->rumah_ibadah->nama_masjid : $this->marbot->rumah_ibadah->nama_mushalla) 
                : 'Rumah Ibadah',
            'created_at' => $this->marbot->created_at,
        ];
    }
}
