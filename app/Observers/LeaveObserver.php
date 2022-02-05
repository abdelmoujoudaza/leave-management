<?php

namespace App\Observers;

use App\Mail\LeaveAllocated;
use App\Mail\LeaveApproved;
use App\Mail\LeaveDemanded;
use App\Mail\LeaveRefused;
use App\Models\Leave;
use Illuminate\Support\Facades\Mail;

class LeaveObserver
{
    /**
     * Handle the Leave "created" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function created(Leave $leave)
    {
        if ($leave->type == 'allocation') {
            Mail::send(new LeaveAllocated($leave));
        } else {
            Mail::send(new LeaveDemanded($leave));
        }
    }

    /**
     * Handle the Leave "updated" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function updated(Leave $leave)
    {
        if ($leave->status == 'approved') {
            Mail::send(new LeaveApproved($leave));
        } else if ($leave->status == 'refused') {
            Mail::send(new LeaveRefused($leave));
        }
    }

    /**
     * Handle the Leave "deleted" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function deleted(Leave $leave)
    {
        //
    }

    /**
     * Handle the Leave "restored" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function restored(Leave $leave)
    {
        //
    }

    /**
     * Handle the Leave "force deleted" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function forceDeleted(Leave $leave)
    {
        //
    }
}
