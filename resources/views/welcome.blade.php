<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Arfil's Landscaping Services</title>
    <!-- Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            /* Set body background to white */
            color: #000000;
            /* Set text color to black */
            scroll-behavior: smooth;
        }

        .nav-item .nav-link {
            color: #343a40;
            /* Default color */
            font-weight: 600;
            text-decoration: none;
            /* Remove underline by default */
            transition: color 0.3s, border-bottom-color 0.3s;
            /* Smooth transition for color and underline */
        }

        .nav-item:hover .nav-link {
            color: #17a2b8 !important;
            /* Success (green) color */
            border-bottom: 2px solid #17a2b8;
            /* Underline on hover */
        }

        .navbar {
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.1);
            /* Add shadow for bottom highlight */
        }

        .jumbotron {
            position: relative;
            background-size: cover;
            color: #000000;
            /* Text color black */
            text-align: center;
            /* Center text */
            padding: 4rem 2rem;
            /* Add padding for better layout */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #ffffff;
            /* Set jumbotron background to white */
        }

        .jumbotron::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            /* Dark overlay */
            backdrop-filter: blur(10px);
            /* Blur effect */
        }

        .jumbotron .container {
            position: relative;
            z-index: 1;
        }

        .jumbotron h1 {
            font-family: 'Roboto', sans-serif;
            /* Roboto font */
            font-weight: 300;
            /* Extra bold */
            color: #000000;
            /* White color for contrast */
        }

        .jumbotron .lead {
            font-family: 'Roboto', sans-serif;
            /* Roboto font */
            font-weight: 100;
            /* Semi-bold */
            color: #000000;
            /* White color for contrast */
        }

        .jumbotron a.btn {
            margin: 0 10px;
            /* Add margin between buttons */
        }

        /* Placeholder styling */
        .placeholder {
            background-color: #f2f2f2;
            /* Light grey background */
            padding: 20px;
            margin-bottom: 20px;
        }

        .placeholder .card-text {
            color: #888888;
            /* Light grey text color */
        }

        .card-background {
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            border-radius: 12px;
            /* Increased radius for a smoother look */
            overflow: hidden;
            /* Ensure content stays within bounds */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Soft shadow for depth */
            transition: transform 0.3s ease;
            /* Smooth transform effect */
        }

        .card-background:hover {
            transform: scale(1.05);
            /* Slightly scale up on hover for a dynamic effect */
        }

        .card-body {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.8);
            /* Slightly more transparent background */
            padding: 1.5rem;
            /* Increased padding for better spacing */
            border-radius: 12px;
            /* Match border radius with the background */
            margin: 1rem;
            /* Add margin to separate from the card border */
            backdrop-filter: blur(8px);
            /* Slight blur for a frosted glass effect */
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow mb-0">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Logo -->
        <a class="navbar-brand" href="">
            <img src="{{ asset('arfil_logo1.png') }}" alt="Arfil's Logo" style="max-width: 55px;">
            Arfil's Landscaping and Swimmingpool Services
        </a>

        <!-- Links -->
        <ul class="navbar-nav ml-auto">
            <!-- About -->
            <li class="nav-item mr-4">
                <a class="nav-link text-dark font-weight-bold" href="#about">About</a>
            </li>

            <li class="nav-item dropdown mr-4">
                <a class="nav-link dropdown-toggle text-dark font-weight-bold" href="#" id="servicesDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Services
                </a>
                <div class="dropdown-menu" aria-labelledby="servicesDropdown">
                    <a class="dropdown-item"
                        href="{{ route('services.byCategory', ['category' => 'landscaping']) }}">Landscaping</a>
                    <a class="dropdown-item"
                        href="{{ route('services.byCategory', ['category' => 'swimmingpool']) }}">Swimming Pool</a>
                    <a class="dropdown-item"
                        href="{{ route('services.byCategory', ['category' => 'renovation']) }}">Renovation</a>
                    <a class="dropdown-item"
                        href="{{ route('services.byCategory', ['category' => 'maintenance']) }}">Maintenance</a>
                    <a class="dropdown-item"
                        href="{{ route('services.byCategory', ['category' => 'package']) }}">Packages</a>

                </div>
            </li>

            <li class="nav-item mr-4">
                <a class="nav-link text-dark font-weight-bold" href="#contact">Contact</a>
            </li>

            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <span class="badge badge-danger badge-counter">
                        {{ \App\Models\Notification::where('sent_to', auth()->id())->where('is_read', false)->count() }}
                    </span>
                </a>
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown" style="max-height: 400px; overflow-y: auto;">
                    <h6 class="dropdown-header bg-info">
                        Alerts Center
                    </h6>
                    @php
                        // Fetch unread notifications first, then read notifications
                        $notifications = \App\Models\Notification::where('sent_to', auth()->id())
                            ->orderByRaw('is_read ASC, created_at DESC') // Order by unread first, then newest
                            ->take(20) // Adjust the limit as needed
                            ->get();
                    @endphp

                    @if ($notifications->isNotEmpty())
                        @foreach ($notifications as $notification)
                            <a class="dropdown-item text-center small {{ $notification->is_read ? 'text-gray-500' : 'font-weight-bold text-gray-800' }}"
                                href="{{ route('notifications.markAsRead', $notification->id) }}?redirect={{ urlencode($notification->type === 'Booking' ? route('booking.view', $notification->type_id) : ($notification->type === 'Project' ? route('project.view', $notification->type_id) : ($notification->type === 'Payment' ? route('payments.show', $notification->type_id) : ($notification->type === 'Progress' ? route('progress.view', ['projectId' => $notification->type_id]) : '#')))) }}">
                                <strong
                                    class="{{ $notification->is_read ? '' : 'font-weight-bold' }}">{{ $notification->title }}</strong>
                                - {{ $notification->message }}
                            </a>
                        @endforeach
                    @else
                        <a class="dropdown-item text-center small text-gray-500" href="#">No alerts</a>
                    @endif
                </div>
            </li>











            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- User Dropdown -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @auth
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    @endauth
                    <img class="img-profile rounded-circle" src="man.png">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    @auth
                        <a class="dropdown-item" href="{{ route('quotation.view') }}">
                            <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Quotations
                        </a>

                        <a class="dropdown-item" href="{{ route('booking.index') }}">
                            <i class="fas fa-calendar-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Bookings
                        </a>
                        <a class="dropdown-item" href="{{ route('project.index') }}">
                            <i class="fas fa-briefcase fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Projects
                        </a>

                        <a class="dropdown-item" href="{{ route('payments.index') }}">
                            <i class="fas fa-dollar-sign fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Payments
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('tasklog.index') }}" data-toggle="tooltip"
                            title="View Task Log">
                            <i class="fas fa-tasks fa-sm fa-fw mr-2 text-gray-400"></i>
                            Task Log
                        </a>

                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class="dropdown-item" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Sign in
                        </a>
                        @if (Route::has('register'))
                            <a class="dropdown-item" href="{{ route('register') }}">
                                <i class="fas fa-user-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                                Sign Up
                            </a>
                        @endif
                    @endauth
                </div>
            </li>

        </ul>
    </nav>
    <!-- End of Topbar -->

    <!-- Page Content -->

    <!-- Carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"
        style="max-height: 400px; overflow: hidden; width: 100%;">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="{{ asset('landscaping.jpg') }}" alt="Landscape Services"
                    style="object-fit: cover; max-height: 400px; filter: brightness(80%);">
                <div class="carousel-caption d-none d-md-block text-left">
                    <h5 class="font-weight-bold text-white display-4">Landscape Services</h5>
                    <p class="lead text-white">Elevating outdoor spaces with inspired designs and meticulous care.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="{{ asset('swimmingpool.jpg') }}" alt="SwimmingPool Services"
                    style="object-fit: cover; max-height: 400px; filter: brightness(80%);">
                <div class="carousel-caption d-none d-md-block text-left">
                    <h5 class="font-weight-bold text-white display-4">Swimming Pool Services</h5>
                    <p class="lead text-white">Dive into luxury with our expert swimming pool design and maintenance
                        services.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="{{ asset('renovation.jpg') }}" alt="Renovation Services"
                    style="object-fit: cover; max-height: 400px; filter: brightness(80%);">
                <div class="carousel-caption d-none d-md-block text-left">
                    <h5 class="font-weight-bold text-white display-4">Renovation Services</h5>
                    <p class="lead text-white">Revitalize your space with our expert renovation solutions.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="{{ asset('maintenance.jpg') }}" alt="Maintenance Services"
                    style="object-fit: cover; max-height: 400px; filter: brightness(80%);">
                <div class="carousel-caption d-none d-md-block text-left">
                    <h5 class="font-weight-bold text-white display-4">Maintenance Services</h5>
                    <p class="lead text-white">Reliable maintenance solutions for every season.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!-- End of Carousel -->

    <!-- Jumbotron -->
    <div class="container-fluid p-0">
        <div class="jumbotron jumbotron-fluid text-white"
            style="background: linear-gradient(135deg, #28a745, #218838);">
            <div class="container">
                <h1 class="display-3 font-weight-bold">Transform Your Outdoors with Arfil's Landscaping Services</h1>
                <p class="lead">We offer a comprehensive range of services to make your outdoor space beautiful and
                    functional.</p>
                <p>From landscaping to swimming pool design, renovation, and maintenance, we have the expertise to bring
                    your vision to life.</p>
                <hr class="my-4 border-light">
                <div class="d-flex flex-column flex-md-row">
                    <a class="btn btn-lg btn-light btn-outline-success mr-md-3 mb-3 mb-md-0"
                        href="{{ route('booking.form') }}" role="button"
                        style="transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">Schedule Site
                        Visit</a>
                    <a class="btn btn-lg btn-success text-white" href="{{ route('quotation.create') }}"
                        role="button"
                        style="transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">Get a Quote</a>
                </div>
            </div>
        </div>
    </div>



    <!-- Divider -->
    <div class="border-top my-4"></div>

    <style>
        /* Card hover effects */
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Button hover effects */
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Animation classes */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .slide-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .zoom-in {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .zoom-in.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Custom styles */
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-outline-success,
        .btn-outline-primary,
        .btn-outline-danger,
        .btn-outline-warning {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
    </head>

    <body>
        <h1 class="display-5 text-center" style="font-weight: 300; margin-top: 20px;">Our Services</h1>

        <div class="placeholder"
            style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 30px; background: rgba(0, 0, 0, 0.1);">
            <!-- Card 1: Landscaping -->
            <div class="card hover-effect" style="width: 300px; cursor: pointer; background: rgba(76, 175, 80, 0.1);"
                onclick="window.location.href='{{ route('services.byCategory', ['category' => 'landscaping']) }}'">
                <img src="landscaping.jpg" class="card-img-top" alt="Landscaping"
                    style="width: 100%; height: 180px; object-fit: cover;">
                <div class="card-body text-center">
                    <i class="fas fa-tree fa-2x" style="color: #4CAF50;"></i>
                    <h5 class="card-title" style="font-weight: 800; margin-top: 10px;">Landscaping</h5>
                    <p class="card-text">Transform your outdoor space with our professional landscaping services.</p>
                    <a href="{{ route('services.byCategory', ['category' => 'landscaping']) }}"
                        class="btn btn-outline-success">View Services</a>
                </div>
            </div>

            <!-- Card 2: Swimming Pool -->
            <div class="card hover-effect" style="width: 300px; cursor: pointer; background: rgba(2, 136, 209, 0.1);"
                onclick="window.location.href='{{ route('services.byCategory', ['category' => 'swimmingpool']) }}'">
                <img src="swimmingpool.jpg" class="card-img-top" alt="Swimming Pool"
                    style="width: 100%; height: 180px; object-fit: cover;">
                <div class="card-body text-center">
                    <i class="fas fa-swimmer fa-2x" style="color: #0288D1;"></i>
                    <h5 class="card-title" style="font-weight: 800; margin-top: 10px;">Swimming Pool</h5>
                    <p class="card-text">Dive into luxury with our professional swimming pool design and maintenance
                        services.</p>
                    <a href="{{ route('services.byCategory', ['category' => 'swimmingpool']) }}"
                        class="btn btn-outline-primary">View Services</a>
                </div>
            </div>

            <!-- Card 3: Renovation -->
            <div class="card hover-effect" style="width: 300px; cursor: pointer; background: rgba(230, 74, 25, 0.1);"
                onclick="window.location.href='{{ route('services.byCategory', ['category' => 'renovation']) }}'">
                <img src="renovation.jpg" class="card-img-top" alt="Renovation"
                    style="width: 100%; height: 180px; object-fit: cover;">
                <div class="card-body text-center">
                    <i class="fas fa-tools fa-2x" style="color: #E64A19;"></i>
                    <h5 class="card-title" style="font-weight: 800; margin-top: 10px;">Renovation</h5>
                    <p class="card-text">Revitalize your space with our renovation services, tailored to your vision.
                    </p>
                    <a href="{{ route('services.byCategory', ['category' => 'renovation']) }}"
                        class="btn btn-outline-danger">View Services</a>
                </div>
            </div>

            <!-- Card 4: Maintenance -->
            <div class="card hover-effect" style="width: 300px; cursor: pointer; background: rgba(255, 152, 0, 0.1);"
                onclick="window.location.href='{{ route('services.byCategory', ['category' => 'maintenance']) }}'">
                <img src="maintenance1.jpg" class="card-img-top" alt="Maintenance"
                    style="width: 100%; height: 180px; object-fit: cover;">
                <div class="card-body text-center">
                    <i class="fas fa-wrench fa-2x" style="color: #FF9800;"></i>
                    <h5 class="card-title" style="font-weight: 800; margin-top: 10px;">Maintenance</h5>
                    <p class="card-text">Keep your property in top shape with our reliable maintenance services.</p>
                    <a href="{{ route('services.byCategory', ['category' => 'maintenance']) }}"
                        class="btn btn-outline-warning">View Services</a>
                </div>
            </div>

            <!-- Card 5: Package -->
            <div class="card hover-effect" style="width: 300px; cursor: pointer; background: rgba(76, 175, 80, 0.1);"
                onclick="window.location.href='{{ route('services.byCategory', ['category' => 'package']) }}'">
                <img src="background.jpg" class="card-img-top" alt="Package Services"
                    style="width: 100%; height: 180px; object-fit: cover;">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-2x" style="color: #4CAF50;"></i>
                    <h5 class="card-title" style="font-weight: 800; margin-top: 10px;">Package Services</h5>
                    <p class="card-text">Explore our comprehensive service packages tailored to meet all your needs.
                    </p>
                    <a href="{{ route('services.byCategory', ['category' => 'package']) }}"
                        class="btn btn-outline-success">View Packages</a>
                </div>
            </div>
        </div>




        </div>


        <!-- About Us Section -->
        <section id="about"
            style="background: url('2.jpg') no-repeat center center/cover; position: relative; padding: 50px 0;">
            <!-- Backdrop Effect -->
            <div
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); z-index: 1;">
            </div>


            <div style="position: relative; z-index: 2;">
                <h1 class="display-5 text-center" style="font-weight: 400; margin-top: 40px;">About Us</h1>

                <div class="container text-center mt-5">
                    <div class="row justify-content-center gap-4">
                        <!-- Card 1: Mission -->
                        <div class="col-lg-3 col-md-4 col-sm-6 slide-up">
                            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="icon mb-3">
                                        <i class="fas fa-bullseye fa-3x" style="color: #FF5722;"></i>
                                    </div>
                                    <h5 class="card-title font-weight-bold">Mission</h5>
                                    <p class="card-text text-muted">To provide exceptional landscaping services that
                                        transform
                                        outdoor spaces.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Vision -->
                        <div class="col-lg-3 col-md-4 col-sm-6 slide-up">
                            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="icon mb-3">
                                        <i class="fas fa-eye fa-3x" style="color: #009688;"></i>
                                    </div>
                                    <h5 class="card-title font-weight-bold">Vision</h5>
                                    <p class="card-text text-muted">To become a leading provider of innovative
                                        landscaping
                                        solutions in the region.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Goal -->
                        <div class="col-lg-3 col-md-4 col-sm-6 slide-up">
                            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="icon mb-3">
                                        <i class="fas fa-trophy fa-3x" style="color: #FFC107;"></i>
                                    </div>
                                    <h5 class="card-title font-weight-bold">Goal</h5>
                                    <p class="card-text text-muted">To improve our services and contribute to
                                        environmental
                                        conservation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Contact Us Section -->
        <div id="contact" class="mt-5 text-center">
            <h1 class="display-5" style="font-weight: 400; letter-spacing: 2px;">Contact Us</h1>

            <div class="mt-5">
                <!-- Social Media Buttons -->
                <a href="https://www.facebook.com/profile.php?id=100087594043346"
                    class="btn btn-primary btn-lg mx-3 zoom-in" style="border-radius: 50px; width: 200px;">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://mail.google.com/mail/u/0/#inbox?compose=jrjtXFBjqfMzNMcCpJTDGhPJRdkVjFQVJCkKrZlcNZPzQqJFBXcGMdvbnZsxqSgQgGDtffdG"
                    class="btn btn-danger btn-lg mx-3 zoom-in" style="border-radius: 50px; width: 200px;">
                    <i class="far fa-envelope"></i> Gmail
                </a>
                <a href="https://twitter.com/" class="btn btn-info btn-lg mx-3 zoom-in"
                    style="border-radius: 50px; width: 200px;">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
                <a href="https://www.instagram.com/" class="btn btn-warning btn-lg mx-3 zoom-in"
                    style="border-radius: 50px; width: 200px;">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            </div>
        </div>

        <!-- JavaScript for Intersection Observer -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                        } else {
                            entry.target.classList.remove('visible');
                        }
                    });
                }, {
                    threshold: 0.2
                });

                document.querySelectorAll('.fade-in, .slide-up, .zoom-in').forEach(element => {
                    observer.observe(element);
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Modern Footer -->
        <footer class="bg-light py-3 mt-5">
            <div class="container text-center">
                <span class="text-muted">© 2024 Arfil's Landscaping Services | All rights reserved.</span>
            </div>
        </footer>


        <!-- Bootstrap core JavaScript-->
        <script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js"></script>
        <script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js">
        </script>

        <!-- Core plugin JavaScript-->
        <script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.min.js">
        </script>

        <!-- Custom scripts for all pages-->
        <script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/js/sb-admin-2.min.js"></script>

    </body>

</html>
