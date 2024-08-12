<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\StudentSession;

class SessionReminder extends Notification
{
     use Queueable;

    protected $session;

    /**
     * Create a new notification instance.
     */
    public function __construct(StudentSession $session)
    {
        $this->session = $session;
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
                    ->subject('Session Reminder')
                    ->line('You have an upcoming session with ' . $this->session->student->first_name)
                    ->line('Scheduled at: ' . $this->session->start_time->format('Y-m-d H:i:s'))
                    ->line('Please be prepared!')
                    // ->action('View Session', url('/sessions/' . $this->session->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'session_id' => $this->session->id,
            'start_time' => $this->session->start_time,
            'end_time' => $this->session->end_time,
        ];
    }
}
