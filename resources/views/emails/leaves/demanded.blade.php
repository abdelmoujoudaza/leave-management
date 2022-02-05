<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ __('Demanded title', ['employee' => $leave->user->firstname . ' ' . $leave->user->lastname]) }}</h2>
        <p>{{ __('Demanded body', ['employee' => $leave->user->firstname . ' ' . $leave->user->lastname]) }}</p>
    </body>
</html>
