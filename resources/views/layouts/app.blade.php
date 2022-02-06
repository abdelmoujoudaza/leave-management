<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="flex flex-row min-h-screen bg-white text-gray-800" x-data="{ open: true }">
            @include('sidebar')
            <main class="main flex flex-col flex-grow transition-all duration-150 ease-in">
                @livewire('navigation-menu')
                <div class="main-content flex flex-col flex-grow p-4">
                    <!-- Page Heading -->
                    @isset ($header)
                        <header class="font-bold text-2xl text-gray-700">
                            {{ $header }}
                        </header>
                    @endisset

                    <div class="flex flex-col flex-grow bg-white mt-4">
                        <!-- Page Content -->
                        {{ $slot }}
                    </div>
                </div>
                <footer class="footer px-4 py-6">
                    <div class="footer-content">
                        <p class="text-sm text-gray-600 text-center">© Leave CRM 2022. All rights reserved.</p>
                    </div>
                </footer>
            </main>
        </div>
        @stack('modals')

        @livewireScripts
    </body>
</html>
