<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRefused extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->leave->user->email)
                ->subject(__('Refused title'))
                ->view('emails.leaves.refused', ['leave' => $this->leave]);
    }
}
