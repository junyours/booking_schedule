@if (auth()->user()->usertype !== 'user')
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion print-hide" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15">
                <!-- Optional Icon here -->
            </div>
            <div class="sidebar-brand-text mx-3 text-white">Arfil's Admin</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-pie text-tech-green"></i>
                <span class="text-white">Dashboard</span>
            </a>
        </li>

        <!-- Nav Item - Rates (Only for admin) -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('rates.index') }}">
                <i class="fas fa-money-bill-wave text-tech-green"></i>
                <span class="text-white">Rates</span>
            </a>
        </li>

        <!-- Nav Item - Services -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseServices"
                aria-expanded="false" aria-controls="collapseServices">
                <i class="fas fa-seedling text-tech-green"></i>
                <span class="text-white">Services</span>
            </a>
            <div id="collapseServices" class="collapse" aria-labelledby="headingServices" data-parent="#accordionSidebar">
                <div class="bg-dark py-2 collapse-inner rounded">
                    <a class="collapse-item text-white" href="{{ route('archive.index') }}">
                        <i class="fas fa-archive"></i> Archived Services
                    </a>
                    <a class="collapse-item text-white" href="{{ route('landscape') }}">
                        <i class="fas fa-tree"></i> Landscaping Services
                    </a>
                    <a class="collapse-item text-white" href="{{ route('swimmingpool') }}">
                        <i class="fas fa-swimmer"></i> Swimmingpool Services
                    </a>
                    <a class="collapse-item text-white" href="{{ route('renovation') }}">
                        <i class="fas fa-tools"></i> Renovation Services
                    </a>
                    <a class="collapse-item text-white" href="{{ route('package') }}">
                        <i class="fas fa-cogs"></i> Packages
                    </a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Bookings (Visible for both admin and super_admin) -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('booking.adminBooking') }}">
                <i class="fas fa-project-diagram text-tech-green"></i>
                <span class="text-white">Bookings</span>
            </a>
        </li>

        @if (auth()->user()->usertype === 'admin')
            <!-- Nav Item - Projects (Visible for admin) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('project.adminIndex') }}">
                    <i class="fas fa-user-friends text-tech-green"></i>
                    <span class="text-white">Projects</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Nav Item - Payments (Only for admin) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.payments.index') }}">
                    <i class="fas fa-cash-register text-tech-green"></i>
                    <span class="text-white">Payments</span>
                </a>
            </li>

            <!-- Nav Item - Reports -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
                    aria-expanded="false" aria-controls="collapseReports">
                    <i class="fas fa-file-alt text-tech-green"></i>
                    <span class="text-white">Reports</span>
                </a>
                <div id="collapseReports" class="collapse" aria-labelledby="headingReports"
                    data-parent="#accordionSidebar">
                    <div class="bg-dark py-2 collapse-inner rounded">
                        <a class="collapse-item text-white" href="{{ route('reports.rates') }}">
                            <i class="fas fa-chart-line"></i> Payments Report
                        </a>
                    </div>
                </div>
            </li>

        @else
            <!-- Nav Item - Projects (Visible for super_admin) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('project.adminIndex') }}">
                    <i class="fas fa-user-friends text-tech-green"></i>
                    <span class="text-white">Projects</span>
                </a>
            </li>
        @endif

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('accordionSidebar');
        const sidebarState = localStorage.getItem('sidebarState');

        if (sidebarState === 'collapsed') {
            sidebar.classList.add('toggled');
        }

        document.getElementById('sidebarToggle').addEventListener('click', function() {
            sidebar.classList.toggle('toggled');
            if (sidebar.classList.contains('toggled')) {
                localStorage.setItem('sidebarState', 'collapsed');
            } else {
                localStorage.removeItem('sidebarState');
            }
        });
    });
</script>

{{-- <style>
    /* Sidebar */
    .sidebar {
        background-color: #1e2a3a; /* Dark blue-gray */
        transition: all 0.3s;
        min-height: 100vh;
        z-index: 10;
    }

    /* Hover Effect */
    .nav-item .nav-link:hover {
        background-color: #2c3e50; /* Slightly lighter blue-gray */
        border-radius: 5px;
    }

    .nav-item .nav-link.active {
        background-color: #16a085; /* Tech green */
    }

    /* Text & Icon */
    .nav-link i {
        font-size: 1.2em;
    }

    .nav-link span {
        font-size: 1.1em;
        padding-left: 10px;
    }

    .collapse-inner .collapse-item {
        font-size: 1em;
    }

    /* Collapse Effect */
    .collapse-inner {
        background-color: #34495e;
        border-radius: 10px;
    }

    /* Sidebar Toggle */
    #sidebarToggle {
        background-color: #2980b9; /* Tech blue */
        padding: 12px;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Active Sidebar Link */
    .nav-item.active > .nav-link {
        background-color: #16a085;
    }

    /* Branding */
    .sidebar-brand .sidebar-brand-text {
        font-weight: 600;
        color: white;
    }

    /* Modern Sidebar Toggle */
    .sidebar.toggled {
        width: 80px;
    }
    .sidebar.toggled .nav-link {
        text-align: center;
    }
    .sidebar.toggled .nav-item {
        padding-left: 0;
    }

    /* Tech Green Text */
    .text-tech-green {
        color: #16a085 !important; /* Tech green color */
    }
</style> --}}
