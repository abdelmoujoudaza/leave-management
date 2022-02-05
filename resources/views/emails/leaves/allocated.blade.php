<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ __('Allocated title') }}</h2>
        <p>
            {{
                __('Allocated body', [
                    'manager' => $leave->approvedBy->firstname . ' ' . $leave->approvedBy->lastname,
                    'type'    => $leave->leaveType->name
                ])
            }}
        </p>
    </body>
</html>
