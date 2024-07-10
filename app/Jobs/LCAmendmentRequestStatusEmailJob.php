<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LCRequestStatusUpdateEmail;
use App\Notifications\LCAmendmentRequestStatusUpdateEmail;

class LCAmendmentRequestStatusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $amendment_lc_request;
    /**
     * Create a new job instance.
     */
    public function __construct($amendment_lc_request)
    {
        $this->amendment_lc_request = $amendment_lc_request;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::where('id',1)->get();
        Notification::send($users, new LCAmendmentRequestStatusUpdateEmail($this->amendment_lc_request));
    }
}
