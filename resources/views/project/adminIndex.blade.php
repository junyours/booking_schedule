@extends('layouts.apps')
@php
    use App\Models\Service;
@endphp

@section('content')
    <title>
        Arfil's Landscaping Services</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <div class="card shadow-sm rounded-lg border-1">
        <div class="card shadow-sm rounded-lg border-1">
            <div class="card-header stylish-header text-black">
                <h1>Projects</h1>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif



                <div class="card-body">
                    <!-- Filter and Sort Form -->
                    <form action="{{ route('project.adminIndex') }}" method="GET" class="mb-4">
                        <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->

                            <!-- Project Status Filter -->
                            <div class="me-2"> <!-- Added margin to the right -->
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <select name="project_status" class="form-select form-select-sm"
                                        style="max-width: 120px;">
                                        <option value="">All Statuses</option>
                                        <option value="pending"
                                            {{ request('project_status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="active"
                                            {{ request('project_status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="hold" {{ request('project_status') == 'hold' ? 'selected' : '' }}>
                                            On Hold
                                        </option>
                                        <option value="cancel"
                                            {{ request('project_status') == 'cancel' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                        <option value="finish"
                                            {{ request('project_status') == 'finish' ? 'selected' : '' }}>
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




                    @if ($projects->isEmpty())
                        <p class="text-muted">You do not have any projects at this time.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Name</th>
                                        <th>Service Name</th>
                                        <th>Site Visit Date</th>
                                        <th>Address</th>
                                        <th>Province</th>
                                        <th>City</th>
                                        <th>Lot Area</th>
                                        <th>Start Date</th>
                                        <th>Cost</th>
                                        <th>Discount</th>
                                        <th>Total Cost</th>
                                        <th>Total Paid</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ $project->booking->id }}</td>
                                            <td>{{ $project->booking->name }}</td>
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
                                                    {{-- Display the first service --}}
                                                    <span class="first-service">{{ $services->first()->name }}</span>

                                                    {{-- If there are more than one service, display "Show all" and the rest of the services --}}
                                                    @if ($services->count() > 1)
                                                        {{-- "Show all" link placed under the first service --}}
                                                        <div class="dots" onclick="toggleServices(this)"
                                                            style="cursor: pointer; font-size: 12px; color: blue; margin-top: 5px;">
                                                            Show all</div>

                                                        {{-- Hidden container for the remaining services --}}
                                                        <div class="remaining-services"
                                                            style="display: none; margin-top: 5px;">
                                                            {{-- Add a comma before displaying the rest of the services --}}
                                                            ,
                                                            @foreach ($services->slice(1) as $service)
                                                                <div>{{ $service->name }}@if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        {{-- "Show less" link placed under the services --}}
                                                        <div class="hide-services" onclick="toggleServices(this)"
                                                            style="cursor: pointer; display: none; font-size: 12px; color: blue; margin-top: 5px;">
                                                            Show less</div>
                                                    @endif
                                                @else
                                                    No services found
                                                @endif
                                            </td>

                                            <script>
                                                function toggleServices(element) {
                                                    var remainingServices = element.closest('td').querySelector('.remaining-services');
                                                    var dots = element.closest('td').querySelector('.dots');
                                                    var hideLink = element.closest('td').querySelector('.hide-services');

                                                    // Toggle between showing and hiding the services
                                                    if (remainingServices.style.display === 'none' || remainingServices.style.display === '') {
                                                        // Show the remaining services and hide the "Show all"
                                                        remainingServices.style.display = 'block';
                                                        dots.style.display = 'none'; // Hide the "Show all"
                                                        hideLink.style.display = 'block'; // Show the "Show less" option
                                                    } else {
                                                        // Hide the remaining services and show the "Show all" again
                                                        remainingServices.style.display = 'none';
                                                        dots.style.display = 'block'; // Show the "Show all" again
                                                        hideLink.style.display = 'none'; // Hide the "Show less" option
                                                    }
                                                }
                                            </script>


                                            <script>
                                                function toggleServices(element) {
                                                    var remainingServices = element.closest('td').querySelector('.remaining-services');
                                                    var dots = element.closest('td').querySelector('.dots');
                                                    var hideLink = element.closest('td').querySelector('.hide-services');

                                                    // Toggle between showing and hiding the services
                                                    if (remainingServices.style.display === 'none' || remainingServices.style.display === '') {
                                                        // Show the remaining services and hide the dots
                                                        remainingServices.style.display = 'inline';
                                                        dots.style.display = 'none'; // Hide the dots after showing all
                                                        hideLink.style.display = 'inline'; // Show the 'Show less' option
                                                    } else {
                                                        // Hide the remaining services and show the dots again
                                                        remainingServices.style.display = 'none';
                                                        dots.style.display = 'inline'; // Show the dots to allow expanding again
                                                        hideLink.style.display = 'none'; // Hide the 'Show less' option
                                                    }
                                                }
                                            </script>



                                            <td>{{ \Carbon\Carbon::parse($project->booking->site_visit_date)->format('F j, Y') }}
                                            </td>
                                            <td>{{ $project->booking->address }}</td>
                                            <td>{{ $project->booking->province }}</td>
                                            <td>{{ $project->booking->city }}</td>
                                            <td>{{ $project->lot_area }} sqm</td>
                                            <td>{{ \Carbon\Carbon::parse($project->start_date)->format('F j, Y') }}</td>
                                            <td>₱{{ number_format($project->cost, 2) }}</td>
                                            <td>{{ $project->discount }}%</td>
                                            <td>₱{{ number_format($project->total_cost, 2) }}</td>
                                            <td>₱{{ number_format($project->total_paid, 2) }}</td>

                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($project->project_status == 'pending') badge-warning 
                                                    @elseif($project->project_status == 'active') badge-success 
                                                    @elseif($project->project_status == 'hold') badge-danger    
                                                    @elseif($project->project_status == 'finish') badge-primary
                                                    @elseif($project->project_status == 'cancel') badge-secondary @endif">

                                                    @if ($project->project_status == 'pending')
                                                        <i class="fas fa-hourglass-half"></i>
                                                    @elseif($project->project_status == 'active')
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    @elseif($project->project_status == 'hold')
                                                        <i class="fas fa-pause-circle"></i>
                                                    @elseif($project->project_status == 'finish')
                                                        <i class="fas fa-check"></i>
                                                    @elseif($project->project_status == 'cancel')
                                                        <i class="fas fa-times-circle"></i>
                                                    @endif

                                                    {{ ucfirst($project->project_status) }}
                                                </span>
                                            </td>




                                            <td>
                                                <div
                                                    style="display: flex; justify-content: start; align-items: center; gap: 10px; padding: 8px 0;">
                                                    <!-- Dropdown for View, Edit, Hold, and Payment Options -->
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false"
                                                            style="background-color: transparent; border: none; color: #007bff;">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <!-- View Project Button -->
                                                            <a href="{{ route('project.adminShow', $project->id) }}"
                                                                class="dropdown-item" data-toggle="tooltip"
                                                                title="View Project">
                                                                <i class="fas fa-eye" style="color: #17a2b8;"></i>
                                                                <!-- Teal color for View -->
                                                                View Project
                                                            </a>

                                                            <!-- Edit Project Button -->
                                                            @if ($project->project_status !== 'cancel' && $project->project_status !== 'finish')
                                                                <a href="{{ route('project.edit', $project->id) }}"
                                                                    class="dropdown-item" data-toggle="tooltip"
                                                                    title="Edit Project">
                                                                    <i class="fas fa-edit" style="color: #007bff;"></i>
                                                                    <!-- Blue color for Edit -->
                                                                    Edit Project
                                                                </a>
                                                            @endif


                                                            <!-- Payment Button - Show only if total_paid < total_cost -->
                                                            @if ($project->total_paid < $project->total_cost && $project->project_status !== 'cancel')
                                                                <a href="{{ route('payment.payment', ['projectId' => $project->id]) }}"
                                                                    class="dropdown-item" data-toggle="tooltip"
                                                                    title="Make Payment">
                                                                    <i class="fas fa-credit-card"
                                                                        style="color: #28a745;"></i>
                                                                    <!-- Green color for Payment -->
                                                                    Make Payment
                                                                </a>
                                                            @endif

                                                            <!-- Hold Project Button - Show only if project_status is 'active' -->
                                                            @if ($project->project_status === 'active')
                                                                <button class="dropdown-item" data-toggle="modal"
                                                                    data-target="#holdModal{{ $project->id }}"
                                                                    data-toggle="tooltip" title="Hold Project">
                                                                    <i class="fas fa-pause" style="color: #ffc107;"></i>
                                                                    <!-- Yellow color for Hold -->
                                                                    Hold Project
                                                                </button>
                                                            @endif

                                                            <!-- Activate Project Button - Show only if project_status is 'hold' -->
                                                            @if ($project->project_status === 'hold')
                                                                <button class="dropdown-item" data-toggle="modal"
                                                                    data-target="#activateModal{{ $project->id }}"
                                                                    data-toggle="tooltip" title="Activate Project">
                                                                    <i class="fas fa-check" style="color: #17a2b8;"></i>
                                                                    <!-- Teal color for Activate -->
                                                                    Activate Project
                                                                </button>
                                                            @endif

                                                            @if ($project->project_status !== 'finish' && $project->project_status !== 'cancel')
                                                                <button class="dropdown-item" data-toggle="modal"
                                                                    data-target="#cancelModal{{ $project->id }}"
                                                                    data-toggle="tooltip" title="Cancel Project">
                                                                    <i class="fas fa-times-circle"
                                                                        style="color: #dc3545;"></i>
                                                                    <!-- Red color for Cancel -->
                                                                    Cancel Project
                                                                </button>
                                                            @endif


                                                        </div>
                                                        <!-- Cancel Modal -->
                                                        <div class="modal fade" id="cancelModal{{ $project->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="cancelModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="cancelModalLabel">
                                                                            Confirm Cancel</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to cancel this project? This
                                                                        action cannot be undone.
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                        <form
                                                                            action="{{ route('project.cancel', $project->id) }}"
                                                                            method="POST" class="confirm-form">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button type="submit"
                                                                                class="btn btn-danger confirm-button"
                                                                                data-action="cancel">
                                                                                Confirm Cancel
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Hold Modal -->
                                                        <div class="modal fade" id="holdModal{{ $project->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="holdModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="holdModalLabel">
                                                                            Confirm Hold</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to put this project on hold?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <form
                                                                            action="{{ route('project.hold', $project->id) }}"
                                                                            method="POST" class="confirm-form">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button type="submit"
                                                                                class="btn btn-warning confirm-button"
                                                                                data-action="hold">
                                                                                Confirm Hold
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Activate Modal -->
                                                        <div class="modal fade" id="activateModal{{ $project->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="activateModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="activateModalLabel">
                                                                            Confirm Activation</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to activate this project?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <form
                                                                            action="{{ route('project.activate', $project->id) }}"
                                                                            method="POST" class="confirm-form">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button type="submit"
                                                                                class="btn btn-success confirm-button"
                                                                                data-action="activate">
                                                                                Confirm Activation
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </td>

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

            <style>
                /* Container styling to remove padding for full-width table */
                .container-fluid {
                    padding: 0;
                }

                /* Table styling */
                .table {
                    font-size: 15px;
                    /* Increase font size */
                }

                th,
                td {
                    padding: 15px;
                    /* Increase cell padding */
                }

                th {
                    background-color: #d3d3d3;
                    /* Grey background for table headers */
                    color: #333;
                    /* Optional: change text color for better visibility */
                }
            </style>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                document.querySelectorAll('.confirm-form').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault(); // Prevent default form submission

                        // Get the button element and action type
                        const button = form.querySelector('.confirm-button');
                        const actionType = button.getAttribute('data-action');

                        // Change button text based on action type
                        if (actionType === 'cancel') {
                            button.textContent = 'Confirming Cancel...';
                        } else if (actionType === 'hold') {
                            button.textContent = 'Confirming Hold...';
                        } else if (actionType === 'activate') {
                            button.textContent = 'Confirming Activation...';
                        }

                        // Disable the button to prevent multiple clicks
                        button.disabled = true;

                        // Submit the form with SweetAlert confirmation
                        form.submit();

                        // SweetAlert confirmation message
                        Swal.fire({
                            title: `Project ${actionType.charAt(0).toUpperCase() + actionType.slice(1)}d!`,
                            text: `The project has been successfully ${actionType}d.`,
                            icon: 'success',
                            timer: 2000,

                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload(); // Reload the page after confirmation
                            }
                        });
                    });
                });
            </script>

        @endsection
