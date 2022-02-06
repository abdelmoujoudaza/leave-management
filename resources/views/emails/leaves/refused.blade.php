@component('mail::message')
# {!! __('Refused title') !!}

{!!
    __('Refused body', [
        'start'   => date('d/m/Y', strtotime($leave->start_date)),
        'end'     => date('d/m/Y', strtotime($leave->end_date)),
        'manager' => $leave->approvedBy->firstname . ' ' . $leave->approvedBy->lastname
    ])
!!}
@endcomponent
