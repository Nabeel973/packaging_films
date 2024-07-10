<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LCAmendmentRequestStatusUpdateEmail extends Notification
{
    use Queueable;
    protected $amendment_lc_request;

    /**
     * Create a new notification instance.
     */
    public function __construct($amendment_lc_request)
    {
        $this->amendment_lc_request = $amendment_lc_request;
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
    public function toMail(object $notifiable): MailMessage
    {
        $statusName = $this->amendment_lc_request->status ? $this->amendment_lc_request->status->name : 'Unknown';

        return (new MailMessage)
                    ->subject('Status Update for LC Amendment Request ' . $this->amendment_lc_request->id . ' | '. ' against LC' . ' | ' .  $this->amendment_lc_request->lcRequest->id . ' | ' . $this->amendment_lc_request->lcRequest->shipment_name)
                    ->line('Dear User,')
                    ->line('Please note the following status update in respect of LC Amendment Request' . $this->amendment_lc_request->id . ' | '. ' against LC ' . ' | ' .  $this->amendment_lc_request->lcRequest->id . ' | ' . $this->amendment_lc_request->lcRequest->shipment_name . ':')
                    ->line('Current Status: ' . $statusName)
                    ->line('Regards,')
                    ->salutation('System Admin'); // Set an empty salutation to remove the default "Regards, Laravel";
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
