<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <div class="container-fluid">
        <!-- Logo positioned to the far left -->
        @auth
            @if (Auth::user()->usertype != 'super_admin' && Auth::user()->usertype != 'admin')
                <a class="navbar-brand mr-auto" href="{{ route('welcome') }}" style="display: flex; align-items: center;">
                    <img src="{{ asset('arfil_logo1.png') }}" alt="Arfil's Logo" style="max-width: 55px; margin-right: 10px;">
                    <span>Arfil's Landscaping and Swimmingpool Services</span>
                </a>
            @endif
        @endauth

        <!-- Navbar items that should stay in place -->
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                @auth
                    @if (Auth::user()->usertype != 'super_admin' && Auth::user()->usertype != 'admin')
                        <!-- About -->
                        <li class="nav-item mr-4">
                            <a class="nav-link text-dark" href="#about">About</a>
                        </li>

                        <li class="nav-item dropdown mr-4">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="servicesDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Services
                            </a>
                            <div class="dropdown-menu" aria-labelledby="servicesDropdown">
                                <a class="dropdown-item"
                                    href="{{ route('services.byCategory', ['category' => 'landscaping']) }}">Landscaping</a>
                                <a class="dropdown-item"
                                    href="{{ route('services.byCategory', ['category' => 'swimmingpool']) }}">Swimming
                                    Pool</a>
                                <a class="dropdown-item"
                                    href="{{ route('services.byCategory', ['category' => 'renovation']) }}">Renovation</a>
                                <a class="dropdown-item"
                                    href="{{ route('services.byCategory', ['category' => 'maintenance']) }}">Maintenance</a>
                                <a class="dropdown-item"
                                    href="{{ route('services.byCategory', ['category' => 'package']) }}">Packages</a>

                            </div>
                        </li>


                        <!-- Contact -->
                        <li class="nav-item mr-4">
                            <a class="nav-link text-dark" href="#contact">Contact</a>
                        </li>
                    @endauth
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



                @endif

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Dropdown -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @auth
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                        @endauth
                        <img class="img-profile rounded-circle" src="{{ asset('man.png') }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        @auth
                            @if (Auth::user()->usertype == 'super_admin' || Auth::user()->usertype == 'admin')
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @else
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
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @endif
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
        </div>
    </div>
</nav>
