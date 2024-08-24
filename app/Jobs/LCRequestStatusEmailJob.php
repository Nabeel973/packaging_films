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

class LCRequestStatusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $lc_request;

    /**
     * Create a new job instance.
     */
    public function __construct($lc_request)
    {
        $this->lc_request = $lc_request;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::where('status',1)->get();
        // $users = User::where('id',1)->get();
        Notification::send($users, new LCRequestStatusUpdateEmail($this->lc_request));
    }
}
