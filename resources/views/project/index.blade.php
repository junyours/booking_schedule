@extends('layouts.app')

@section('title', 'My Projects')

<!-- Move the title and favicon link into the head section -->
@section('head')
    <title>Arfil's Landscaping Services</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
@endsection

@section('content')
    <div class="pricing-factors mb-4">
        <h5>Project Overview</h5>
        <p>Below is a list of all your projects, including their details and current status.</p>
    </div>

    <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">My Projects</h4>
            <div>
                <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter and Sort Form -->
            <form action="{{ route('project.index') }}" method="GET" class="mb-4">
                <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->

                    <!-- Project Status Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <select name="project_status" class="form-select form-select-sm" style="max-width: 120px;">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('project_status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="active" {{ request('project_status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="hold" {{ request('project_status') == 'hold' ? 'selected' : '' }}>On Hold
                                </option>
                                <option value="cancel" {{ request('project_status') == 'cancel' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="finish" {{ request('project_status') == 'finish' ? 'selected' : '' }}>
                                    Finished</option>
                            </select>
                        </div>
                    </div>

                    <!-- Start Date Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>
                    </div>

                    <!-- End Date Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($projects->isEmpty())
                <p class="text-muted">You do not have any projects at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th> Booking ID</th>
                                <th><i class="fas fa-briefcase"></i> Service Name</th>
                                <th><i class="fas fa-calendar-check"></i> Site Visit Date</th>
                                <th><i class="fas fa-map-marker-alt"></i> Address</th>
                                <th><i class="fas fa-map"></i> Province</th>
                                <th><i class="fas fa-city"></i> City</th>
                                <th><i class="fas fa-ruler-combined"></i> Lot Area</th>
                                <th><i class="fas fa-money-bill-wave"></i> Total Cost</th>
                                <th><i class="fas fa-wallet"></i> Total Paid</th>
                                <th><i class="fas fa-tasks"></i> Status</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $project->booking->id }}</td>
                                    <td>
                                        @php
                                            // Check if service_ids is not null and then decode
                                            $serviceIds = $project->service_ids
                                                ? json_decode($project->service_ids)
                                                : [];

                                            // Fetch services based on the IDs only if serviceIds is an array
                                            $services = !empty($serviceIds)
                                                ? \App\Models\Service::whereIn('id', $serviceIds)->get()
                                                : collect();
                                        @endphp

                                        {{-- Check if services were found and display their names --}}
                                        @if ($services->isNotEmpty())
                                            @foreach ($services as $service)
                                                {{ $service->name }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        @else
                                            No services found
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($project->booking->site_visit_date)->format('F j, Y') }}
                                    </td>
                                    <td>{{ $project->booking->address }}</td>
                                    <td>{{ $project->booking->province }}</td>
                                    <td>{{ $project->booking->city }}</td>
                                    <td>{{ $project->lot_area }} sqm</td>
                                    <td>₱{{ number_format($project->total_cost, 2) }}</td>
                                    <td>₱{{ number_format($project->total_paid, 2) }}</td>

                                    <td>
                                        @php
                                            // Set the icon class and badge based on the project status
                                            $statusClasses = [
                                                'pending' => ['fas fa-hourglass-start', 'badge-warning'],
                                                'active' => ['fas fa-check-circle', 'badge-success'],
                                                'hold' => ['fas fa-pause-circle', 'badge-danger'],
                                                'finish' => ['fas fa-check-double', 'badge-primary'],
                                            ];
                                            [$iconClass, $badgeClass] = $statusClasses[$project->project_status] ?? [
                                                'fas fa-question-circle',
                                                'badge-secondary',
                                            ];
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            <i class="{{ $iconClass }}"></i>
                                            {{ ucfirst($project->project_status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <div
                                            style="display: flex; justify-content: center; align-items: center; gap: 10px; padding: 8px 0;">
                                            <a href="{{ route('project.view', $project->id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Project">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="pagination-wrapper">
                {{ $projects->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>

@endsection

@section('styles')
    <style>
        /* General Card Styles */
        .card {
            background-color: transparent !important;  /* Makes the entire card background transparent */
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2); /* Optional: adds a subtle border for visibility */
            overflow: hidden;
        }

        .card-header {
            background-color: transparent !important; /* Makes the header transparent */
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
        }

        .card-body {
            background-color: transparent !important; /* Makes the body of the card transparent */

            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* Pricing Factors Styles */
        .pricing-factors {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            background-color: #f9f9f9;
        }

        .pricing-factors h5 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .pricing-factors p {
            margin-bottom: 0.5rem;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .modal-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            background-color: #007bff;
            color: #ffffff;
        }

        .modal-body {
            padding: 1.5rem;
            background-color: #f9f9f9;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: none;
            background-color: #f9f9f9;
            border-radius: 0 0 12px 12px;
        }

        .pagination-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-wrapper .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-wrapper .page-item {
            margin: 0 2px;
        }

        .pagination-wrapper .page-link {
            padding: 10px 15px;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-info {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-light {
            background-color: #f8f9fa;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .btn-light:hover {
            background-color: #e2e6ea;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $('#projectModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            // Extract info from data-* attributes
            var id = button.data('id');
            var booking_id = button.data('booking_id');
            var service_id = button.data('service_id');
            var lot_area = button.data('lot_area');
            var total_cost = button.data('total_cost');
            var project_status = button.data('project_status');
            var site_visit_date = new Date(button.data('site_visit_date')); // Parse date
            var address = button.data('address');
            var province = button.data('province');
            var city = button.data('city');

            // Update the modal's content
            var modal = $(this);
            modal.find('#modalId').text(id);
            modal.find('#modalBookingId').text(booking_id);
            modal.find('#modalServiceId').text(service_id);
            modal.find('#modalSiteVisitDate').text(site_visit_date.toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));
            modal.find('#modalAddress').text(address);
            modal.find('#modalProvince').text(province);
            modal.find('#modalCity').text(city);
            modal.find('#modalLotArea').text(lot_area);
            modal.find('#modalTotalCost').text('₱' + parseFloat(total_cost).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            // Set the status badge
            var badgeClass = '';
            switch (project_status) {
                case 'pending':
                    badgeClass = 'badge-warning';
                    break;
                case 'active':
                    badgeClass = 'badge-success';
                    break;
                case 'hold':
                    badgeClass = 'badge-danger';
                    break;
                case 'finish':
                    badgeClass = 'badge-primary';
                    break;
            }

            modal.find('#modalStatus').text(project_status.charAt(0).toUpperCase() + project_status.slice(1)).attr(
                'class', 'badge ' + badgeClass);
        });
    </script>
@endsection
