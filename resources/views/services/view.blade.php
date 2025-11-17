<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arfil's Landscaping Services</title>

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

    <!-- Updated CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        /* Reset and Base Styles */
        html,
        body {
            height: 100%;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: url('{{ asset('1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            /* Removed overflow: hidden to allow scrolling */
        }

        /* Navbar Styles */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            height: 60px;
            z-index: 1000;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        /* Backdrop Styles */
        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black */
            backdrop-filter: blur(5px);
            /* Blur effect */
            z-index: 1;
            /* Positioned below main content */
            pointer-events: none;
            /* Allows interaction with main content */
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .backdrop.active {
            opacity: 1;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 90px 30px 30px;
            /* 60px navbar + 30px extra */
            position: relative;
            z-index: 2;
            /* Above the backdrop */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Footer Styles */
        .footer {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            width: 100%;
            height: 60px;
            box-sizing: border-box;
            z-index: 2;
            /* Same as main content */
        }

        /* Profile Image Styles */
        .img-profile {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Button Styles */
        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
            background-color: transparent;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-info:hover {
            transform: scale(1.05);
            background-color: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }

        /* Home Button Positioning and Styles */
        .home-button {
            /* Removed position: fixed; */
            /* Center the button */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            font-size: 1rem;
            border: 2px solid #17a2b8;
            border-radius: 50px;
            background-color: transparent;
            color: #17a2b8;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            /* Space between h1 and button */
        }

        .home-button i {
            margin-right: 8px;
            /* Space between icon and text */
            transition: transform 0.3s ease;
        }

        /* Hover Effects */
        .home-button:hover {
            background-color: #17a2b8;
            color: #fff;
            transform: translateY(-3px);
            /* Slight lift on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .home-button:hover i {
            transform: rotate(20deg);
            /* Icon rotates slightly on hover */
        }

        /* Active State (Optional) */
        .home-button:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        .modern-heading {
            font-weight: 400;
            /* Slightly bolder for better presence */
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
            color: #ffffff;
            /* White for visibility */
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            font-size: 2.5rem;
            /* Adjust as needed */
            transition: color 0.3s ease, transform 0.3s ease;
            /* Add transform for hover */
        }

        /* Underline with Animation */
        .modern-heading::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background-color: #17a2b8;
            /* Primary color for underline */
            bottom: -5px;
            /* Move below the text for better effect */
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            /* Start scaled down */
            border-radius: 2px;
            transition: transform 0.3s ease;
            /* Smooth scaling */
        }

        /* Hover Effect on Heading */
        .modern-heading:hover {
            /* Change color on hover */
            transform: translateY(-5px);
            /* Lift the heading slightly */
        }

        /* Hover Effect on Underline */
        .modern-heading:hover::after {
            transform: translateX(-50%) scaleX(1);
            /* Scale up on hover */
        }

        /* Responsive Typography */
        @media (max-width: 768px) {
            .modern-heading {
                font-size: 2rem;
                /* Adjust font size for smaller screens */
                margin-bottom: 15px;
            }

            .modern-heading::after {
                width: 40px;
                /* Adjust underline width for smaller screens */
            }

            .modern-heading:hover::after {
                transform: translateX(-50%) scaleX(1);
                /* Keep hover effect for small screens */
            }

            .service-container .card {
                width: 90%;
                /* Make cards take up more width on small screens */
            }

            .service-container .card:nth-child(1),
            .service-container .card:nth-child(2),
            .service-container .card:nth-child(3),
            .service-container .card:nth-child(4) {
                animation-delay: 0s;
                /* Remove staggered delays on mobile for faster loading */
            }

            .home-button {
                margin-top: 15px;
                /* Adjust space on smaller screens */
            }
        }

        /* Card Styles with Animations and Hover Effects */
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            flex: 0 1 calc(33.333% - 20px);
            margin-bottom: 30px;
            /* Space below each card */
            width: 300px;
            text-align: center;
            margin: 15px;
            margin-bottom: 30px;

            /* Entrance Animation */
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s forwards;
        }

        /* Staggered Animation Delay for Each Card */
        .service-container .card:nth-child(1) {
            animation-delay: 0.2s;
        }

        .service-container .card:nth-child(2) {
            animation-delay: 0.4s;
        }

        .service-container .card:nth-child(3) {
            animation-delay: 0.6s;
        }

        .service-container .card:nth-child(4) {
            animation-delay: 0.8s;
        }

        /* Hover Effects */
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.1);
        }

        .card-img-top {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card-body {
            flex: 1;
            padding: 20px;
        }

        .card-title {
            font-weight: 800;
            margin-bottom: 15px;
            color: #17a2b8;
            /* Primary color for titles */
        }

        .card-text {
            color: #555;
            line-height: 1.5;
        }

        .card-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .card-footer .btn-outline-info {
            margin: 0;
        }

        /* Container Styles */
        .container {
            position: relative;
            z-index: 2;
        }

        .service-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            /* This will help distribute the space evenly */
            padding: 30px;
            background-color: transparent;
            border-radius: 8px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* Creates three equal columns */
            gap: 30px;
            /* Space between the cards */
            padding: 30px;
            background-color: transparent;
            border-radius: 8px;
        }

        /* Entrance Animations Keyframes */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Optional: Add fadeIn keyframes for other elements */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Add smooth transition and initial state */
        .card-img-top {
            transition: transform 0.3s ease;
            cursor: pointer;
            /* Makes the image clickable */
        }

        /* Class that will be added on click to scale the image */
        .img-scale {
            transform: scale(1.5);
            /* Adjust the scale factor to your liking */
        }
    </style>
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow mb-0">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('welcome') }}">
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
                        href="{{ route('services.byCategory', ['category' => 'package']) }}">packages</a>
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
                        // Fetch the latest notifications for the logged-in user
                        $notifications = \App\Models\Notification::where('sent_to', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->take(20) // Increase if you want to show more notifications
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
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    <img class="img-profile rounded-circle" src="{{ asset('man.png') }}" alt="Profile Image">
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

    <!-- Backdrop -->
    <div class="backdrop active"></div> <!-- Ensure 'active' class is present to make it visible -->

    <!-- Main Content -->
    <div class="main-content">
        <!-- Modern Heading -->
        <h1 class="display-5 modern-heading">
            {{ ucfirst($category) }} Services
        </h1>

        <!-- Home Button Below the Heading -->
        <a href="{{ route('welcome') }}" class="btn btn-outline-info home-button" aria-label="Home">
            <i class="fa fa-home" aria-hidden="true"></i> Home
        </a>

        <div class="container">
            <div class="service-container">
                @if ($services->isEmpty())
                    <p class="text-center">No {{ $category }} services available at the moment.</p>
                @else
                    @foreach ($services as $service)
                        <div class="card">
                            <img src="{{ asset('storage/' . $service->design) }}" class="card-img-top"
                                alt="{{ $service->name }}" loading="lazy">
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->name }}</h5>
                                <p class="card-text">{{ $service->description }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('booking.form') }}" class="btn btn-outline-info">Book Service</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Arfil's Landscaping Services. All rights reserved.</p>
        </div>
    </footer>

    <!-- Optional: JavaScript to Toggle Backdrop (if needed for modals) -->
    <script>
        // Example: Toggle Backdrop for a Modal (if you have modals)
        function toggleBackdrop() {
            const backdrop = document.querySelector('.backdrop');
            backdrop.classList.toggle('active');
        }
        // JavaScript to handle the image click and toggle the scale
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.card-img-top');

            images.forEach((img) => {
                img.addEventListener('click', function() {
                    // Toggle the class to scale the image
                    img.classList.toggle('img-scale');
                });
            });
        });
    </script>

</body>

</html>
