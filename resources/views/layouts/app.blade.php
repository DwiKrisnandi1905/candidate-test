<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Sweetalert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    
        <style>
            /* style for show entries */
            div.dataTables_length select {
                width: 70px;
                padding: 4px 8px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }
        </style>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900 overflow-hidden">
            <!-- view/component/sidebar -->
            <x-sidebar />

            <div class="flex-1 ml-60 flex flex-col overflow-hidden">
                <!-- view/layouts/navigation -->
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Main Content Area -->
                <main class="flex-1 bg-white p-4 overflow-auto">
                    @hasSection('breadcrumb')
                        <div class="mb-4">
                            @yield('breadcrumb')
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
