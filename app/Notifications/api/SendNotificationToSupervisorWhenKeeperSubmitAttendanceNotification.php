<?php

namespace App\Notifications\api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationToSupervisorWhenKeeperSubmitAttendanceNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    public $supervisor;
    public $keeper;

    /**
     * Create a new notification instance.
     */
    public function __construct($supervisor, $keeper)
    {
        //
        $this->supervisor = $supervisor;
        $this->keeper = $keeper;
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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Daily Report Submitted')
            ->line("Hi " . $this->supervisor->fname . ' ' . $this->supervisor->lname . ",")
            ->line($this->keeper->fname . ' ' . $this->keeper->lname . " has submitted their daily report for review.")
            ->action('View Report', url('/reports/'.$this->supervisor->id))
            ->line('Thank you');
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
