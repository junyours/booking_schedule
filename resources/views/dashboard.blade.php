@extends('layouts.apps')

@section('content')
    <div class="bg-overlay">
        <div class="container-fluid mt-2">

            <!-- Page Content -->
            <div class="row">
                <!-- Card 1: Total Services -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-cogs"></i> Total Services
                            </h5>
                            <p class="card-text display-4">{{ $totalServices }}</p>
                            <a href="{{ route('landscape') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Bookings -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-calendar-check"></i> Total Bookings
                            </h5>
                            <p class="card-text display-4">{{ $totalBookings }}</p>
                            <a href="{{ route('booking.adminBooking') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Projects -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-briefcase"></i> Total Projects
                            </h5>
                            <p class="card-text display-4">{{ $totalProjects }}</p>
                            <a href="{{ route('project.adminIndex') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Revenue -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-money-bill-wave"></i> Total
                            </h5>
                            <p class="card-text display-4">₱{{ number_format($totalRevenue, 2) }}</p>
                            <a href="{{ route('reports.rates') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Status Overview Pie Chart -->
            <div class="row mb-4">
                <div class="col-md-6 mb-2"
                    style="display: flex; justify-content: flex-start; padding-right: 10px; background: linear-gradient(to bottom, #eaf9fb, #90c5d1);">
                    <div style="width: 100%; max-width: 550px;">
                        <h4 class="col-12" style="font-weight: bold;">Bookings</h4>
                        <canvas id="bookingStatusChart" width="400" height="400"
                            style="max-width: 100%; height: auto;"></canvas>
                    </div>
                </div>

                <div class="col-md-6 mb-2"
                    style="display: flex; justify-content: flex-start; background: linear-gradient(to bottom, #eaf9fb, #90c5d1);">
                    <div style="width: 100%; max-width: 700px;">
                        <h4 class="col-12" style="font-weight: bold;">Projects</h4>
                        <canvas id="projectStatusChart" width="400" height="300"
                            style="max-width: 100%; height: auto;"></canvas>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-2">
                        <label for="filter" class="form-label">Filter By</label>
                        <select name="filter" id="filter" class="form-select pastel-filter"
                            onchange="this.form.submit()">
                            <option value="yearly" {{ request('filter') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="monthly" {{ request('filter') === 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="year" class="form-label">Select Year</label>
                        <select name="year" id="year" class="form-select pastel-filter"
                            onchange="this.form.submit()">
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    @if (request('filter') === 'monthly')
                        <div class="col-md-4 mb-2">
                            <label for="month" class="form-label">Select Month</label>
                            <select name="month" id="month" class="form-select pastel-filter"
                                onchange="this.form.submit()">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </form>

            <!-- Booking-Projects-Payments Chart -->
            <div class="row mb-4" style="margin: 0;">
                <div class="col-12" style="background: linear-gradient(to bottom, #eaf9fb, #90c5d1); padding: 15px;">
                    <h4 style="font-weight: bold;">Booking-Projects-Payments</h4>
                    <div style="padding: 0;">
                        <canvas id="bookingsPaymentsChart" style="width: 100%; height: 300px;"></canvas>
                    </div>
                </div>
            </div>


            <!-- Popular Services Chart -->
            <div class="row mb-4">
                <div class="col-12" style="background: linear-gradient(to bottom, #eaf9fb, #90c5d1); padding: 15px;">
                <div class="col-md-12 mb-2">
                    <h4 class="col-12" style="font-weight: bold;">Popular Services</h4>
                    <canvas id="servicePopularityChart" width="400" height="200"></canvas>
                </div>
            </div>


        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Booking Status Pie Chart
        const ctxBookingStatus = document.getElementById('bookingStatusChart').getContext('2d');
        const bookingStatusData = @json($bookingStatusData); // Pass PHP array to JavaScript

        const bookingStatusChart = new Chart(ctxBookingStatus, {
            type: 'pie',
            data: {
                labels: bookingStatusData.labels,
                datasets: [{
                    data: bookingStatusData.data,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#17a2b8'
                        }
                    }
                }
            }
        });

        // Project Status Bar Chart
        const ctxProjectStatus = document.getElementById('projectStatusChart').getContext('2d');
        const projectStatusData = @json($projectStatusData); // Pass PHP array to JavaScript

        const projectStatusChart = new Chart(ctxProjectStatus, {
            type: 'bar',
            data: {
                labels: projectStatusData.labels,
                datasets: [{
                    label: 'Project Status',
                    data: projectStatusData.data,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });

        // Dual-axis chart for bookings and payments over time
        const ctxBookingsPayments = document.getElementById('bookingsPaymentsChart').getContext('2d');
        const bookingsPaymentsData = @json($bookingsPaymentsData); // Pass PHP array to JavaScript

        const bookingsPaymentsChart = new Chart(ctxBookingsPayments, {
            type: 'line',
            data: {
                labels: bookingsPaymentsData.labels,
                datasets: [{

                        label: 'Bookings',
                        data: bookingsPaymentsData.bookings,
                        borderColor: '#007bff',
                        yAxisID: 'y1',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Payments',
                        data: bookingsPaymentsData.payments,
                        borderColor: '#28a745',
                        yAxisID: 'y2',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Projects',
                        data: bookingsPaymentsData.projects,
                        borderColor: 'red',
                        yAxisID: 'y3',
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y1: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Bookings',
                        }
                    },
                    y2: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false // only draw the grid for one axis
                        },
                        title: {
                            display: true,
                            text: 'Payments'
                        }
                    },
                    y3: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Projects',
                        }
                    }
                }
            }
        });

        const serviceNames = @json($serviceNames);
        const projectCounts = @json($projectCounts);

        const ctxServicePopularity = document.getElementById('servicePopularityChart').getContext('2d');
        const servicePopularityChart = new Chart(ctxServicePopularity, {
            type: 'bar', // Use bar chart
            data: {
                labels: serviceNames, // Service names as labels
                datasets: [{
                    label: 'Number of Projects',
                    data: projectCounts, // Project counts to represent popularity
                    backgroundColor: '#007bff', // Color for bars
                    borderColor: '#fff', // Border color for bars
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y', // Make it horizontal
                plugins: {
                    legend: {
                        display: false // Disable legend
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)', // Tooltip background color
                        titleColor: '#fff', // Tooltip title color
                        bodyColor: '#fff', // Tooltip body color
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true, // Ensure the chart starts at 0
                        title: {
                            display: true,
                            text: 'Number of Projects'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Services'
                        }
                    }
                }
            }
        });
    </script>




    <style>
        .pastel-filter {
            background-color: #f8f9fa;
            /* Light pastel background */
            color: #495057;
            /* Dark grey text */
            border: 1px solid #ced4da;
            /* Subtle border */
            border-radius: 6px;
            /* Rounded edges */
            padding: 8px 10px;
            /* Add padding */
            font-size: 14px;
            /* Slightly smaller text */
            transition: all 0.3s ease;
            /* Smooth transitions */
        }

        .pastel-filter:hover {
            background-color: #e2e6ea;
            /* Slightly darker pastel on hover */
            border-color: #adb5bd;
            /* Darken the border */
            /* color: #212529; */
            /* Slightly darker text on hover */
        }

        .pastel-filter:focus {
            background-color: #ffffff;
            /* White background on focus */
            border-color: #007bff;
            /* Blue border on focus */
            color: #007bff;
            /* Blue text */
            outline: none;
            /* Remove default focus outline */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Subtle blue glow */
        }


        .bg-overlay {
            background: transparent;
            /* Soft complementary shades to info */
            padding: 20px;
            border-radius: 10px;
            /* color: #004f5a; */
            /* Slightly darker text for readability */
        }

        .card {
            background-color: #eaf9fb;
            /* Light background for cards */
            /* color: #004f5a; */
            /* Dark text for good contrast */
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #c3e6e8;
            /* Subtle border for definition */
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            /* Light shadow for elegance */
        }

        .display-4 {
            font-size: 2rem;
            font-weight: bold;
            color: #0dcaf0;
            /* Vibrant info color for headings */
        }

        .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            transition: background-color 0.3s, border-color 0.3s;
            color: #ffffff;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
    </style>

@endsection
