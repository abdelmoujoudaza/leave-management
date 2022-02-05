<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ __('Approved title') }}</h2>
        <p>
            {{
                __('Approved body', [
                    'start'   => date('d/m/Y', strtotime($leave->start_date)),
                    'end'     => date('d/m/Y', strtotime($leave->end_date)),
                    'manager' => $leave->approvedBy->firstname . ' ' . $leave->approvedBy->lastname
                ])
            }}
        </p>
    </body>
</html>
