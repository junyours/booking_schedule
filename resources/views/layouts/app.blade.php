    <!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Arfils Landsacaping Services') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background-color: #ffffff;
                color: #000000;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                margin: 0;
            }

            .navbar {
                box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.1);
            }

            .sticky-footer {
                background-color: #ffffff;
                text-align: center;
                padding: 10px;
                width: 100%;
                height: 50px;
                font-size: 14px;
                border-top: 1px solid #ddd;
                position: relative;
                bottom: 0;
            }

            .nav-item .nav-link {
                color: #343a40;
                font-weight: 300 !important;
                text-decoration: none;
                transition: color 0.3s ease, border-bottom-color 0.3s ease;
            }

            /* Hover effect for collapse items in Services */
            .collapse-item:hover {
                color: #17a2b8 !important;
                background-color: rgba(255, 255, 255, 0.1) !important;
                text-decoration: underline !important;
            }

            /* Sidebar item hover effect */
            .sidebar .nav-item:hover .nav-link {
                color: #ffffff !important;
                background-color: rgba(23, 162, 184, 0.2) !important;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            /* Navbar hover effect */
            .nav-item:hover .nav-link {
                color: #17a2b8 !important;
                /* Change text color to info on hover */
                border-bottom: 2px solid #17a2b8;
                /* Underline color */
                transition: color 0.3s ease, border-bottom-color 0.3s ease;
            }

            .wrapper {
                display: flex;
                flex: 1;
                flex-direction: row;
                min-height: calc(100vh - 50px);
            }

            .sidebar {
                width: 250px;
                flex-shrink: 0;
            }

            .content {
                flex: 1;
                padding: 20px;
            }
        </style>


        <!-- Custom fonts -->
        <link href="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/fontawesome-free/css/all.min.css"
            rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles -->
        <link href="https://startbootstrap.github.io/startbootstrap-sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
    </head>

    <body class="font-sans antialiased dark:bg-black dark:text-white/50">

        <!-- Authenticated User Content -->
        @auth
            @include('layouts.navbars')

            <div class="wrapper">
                @if (Auth::user()->usertype == 'admin')
                    <!-- Include Sidebar -->
                    @include('layouts.sidebar')
                @endif

                <!-- Main content area -->
                <main class="content">
                    @yield('content')
                </main>
            </div>
        @else
            <!-- Unauthenticated User Content -->
            <!-- Main content area for unauthenticated users like the login page -->
            <main class="content">
                @yield('content')
            </main>
        @endauth

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')

    </body>

    <style>
        .content {
            flex: 1;
            padding: 20px;
            background-image: url('{{ asset('2.jpg') }}');
            background-size: cover;
            /* Ensures the image covers the entire area */
            background-position: center;
            /* Centers the image */
            background-repeat: no-repeat;
            /* Prevents tiling of the image */
            min-height: 100vh;
            /* Ensures the content area is at least the height of the viewport */
            position: relative;
        }

        .content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* Adjust the overlay opacity */
            z-index: 0;
        }

        .content>* {
            position: relative;
            z-index: 1;
        }
    </style>


    </html>
