<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class DepositSuccessful extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $type;
    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $type, $url)
    {
        $this->title = $title ?? 'Default Title'; // Berikan nilai default jika perlu
        $this->message = $message ?? 'Default Message'; // Berikan nilai default jika perlu
        $this->type = $type ?? 'info'; // Berikan nilai default jika perlu
        $this->url = $url ?? '#'; // Berikan nilai default jika perlu
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        Log::info('Notifikasi dipanggil', [
            'title' => $this->title,
            'message' => $this->message,
        ]);
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'url' => $this->url,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'url' => $this->url,
        ];
    }
}