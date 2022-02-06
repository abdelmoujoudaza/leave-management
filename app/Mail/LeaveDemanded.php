<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveDemanded extends Mailable
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
        $emails   = User::role('manager')->pluck('email')->toArray();
        $employee = $this->leave->user->firstname . ' ' . $this->leave->user->lastname;

        return $this->to($emails)
                ->subject(__('Demanded title', ['employee' => $employee]))
                ->markdown('emails.leaves.demanded', ['leave' => $this->leave]);
    }
}
