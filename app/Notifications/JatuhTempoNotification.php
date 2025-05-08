<?php

namespace App\Notifications;

use App\Mail\AdminMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JatuhTempoNotification extends Notification
{
    use Queueable;

    protected $daysLeft;

    public function __construct($daysLeft)
    {
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */

     public function toMail(object $notifiable): AdminMail
     {
         return (new AdminMail($this->daysLeft))
                     ->line('You have ' . $this->daysLeft . ' days left until the due date.')
                     ->action('View Details', url('/'))
                     ->line('Thank you for using our application!');
     }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
