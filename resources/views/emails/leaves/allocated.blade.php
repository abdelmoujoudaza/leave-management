@component('mail::message')
# {!! __('Allocated title') !!}

{!!
    __('Allocated body', [
        'manager' => $leave->approvedBy->firstname . ' ' . $leave->approvedBy->lastname,
        'type'    => $leave->leaveType->name
    ])
!!}
@endcomponent
