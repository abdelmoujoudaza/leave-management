@component('mail::message')
# {!! __('Demanded title', ['employee' => $leave->user->firstname . ' ' . $leave->user->lastname]) !!}

{!! __('Demanded body', ['employee' => $leave->user->firstname . ' ' . $leave->user->lastname]) !!}
@endcomponent
