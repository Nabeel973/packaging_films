<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LCRequestStatusUpdateEmail extends Notification
{
    use Queueable;
    protected $lc_request;
    /**
     * Create a new notification instance.
     */
    public function __construct($lc_request)
    {
        $this->lc_request = $lc_request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $statusName = $this->lc_request->status ? $this->lc_request->status->name : 'Unknown';

        return (new MailMessage)
                    ->subject('Status Update for LC ' . $this->lc_request->id . ' | ' . $this->lc_request->shipment_name)
                    ->line('Dear User,')
                    ->line('Please note the following status update in respect of LC ' . $this->lc_request->id . ' | ' . $this->lc_request->shipment_name . ':')
                    ->line('Current Status: ' . $statusName)
                    ->line('Regards,')
                    ->salutation('System Admin'); // Set an empty salutation to remove the default "Regards, Laravel";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'lc_request_id' => $this->lc_request->id,
            'status_id' => $this->lc_request->status_id,
        ];
    }
}
