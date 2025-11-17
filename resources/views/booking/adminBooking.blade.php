@extends('layouts.apps')

@section('content')
    <title>Arfil's Landscaping Services</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card shadow-sm rounded-lg border-1">
        <div class="card-header stylish-header text-black">
            <h1>Bookings</h1>
        </div>
        <div class="card-body">
            <!-- Filter and Sort Form -->
            <form action="{{ route('booking.adminBooking') }}" method="GET" class="mb-4">
                <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->

                    <!-- Booking Status Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <select name="booking_status" class="form-select form-select-sm custom-dropdown">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed</option>
                                <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="declined" {{ request('booking_status') == 'declined' ? 'selected' : '' }}>
                                    Declined</option>
                                <option value="visited" {{ request('booking_status') == 'visited' ? 'selected' : '' }}>
                                    Visited</option>
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
                    <button type="submit" class="btn btn-primary btn-sm"> <!-- Added 'btn-sm' for smaller size -->
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>



        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Booking Table -->
        @if ($bookings->isEmpty())
            <p class="text-muted">You do not have any bookings at this time. Please contact us to make a booking.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>Site Visit Date</th>
                            <th>Booking Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->name }}</td>
                                <td>{{ $booking->contact }}</td>
                                <td>{{ $booking->email }}</td>
                                <td>{{ $booking->address }}</td>
                                <td>{{ $booking->province }}</td>
                                <td>{{ $booking->city }}</td>
                                <td>
                                    @if ($booking->site_visit_date)
                                        {{ (new DateTime($booking->site_visit_date))->format('F j, Y') }}
                                    @else
                                        No site visit scheduled.
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge 
                                @if ($booking->booking_status == 'pending') badge-warning 
                                @elseif($booking->booking_status == 'confirmed') badge-primary 
                                @elseif($booking->booking_status == 'visited') badge-success 
                                @elseif($booking->booking_status == 'cancelled') badge-danger 
                                @elseif($booking->booking_status == 'declined') badge-danger 
                                @else badge-secondary @endif">
                                        @if ($booking->booking_status == 'pending')
                                            <i class="fas fa-hourglass-half"></i>
                                        @elseif($booking->booking_status == 'confirmed')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($booking->booking_status == 'visited')
                                            <i class="fas fa-check-double"></i>
                                        @elseif($booking->booking_status == 'cancelled')
                                            <i class="fas fa-times-circle"></i>
                                        @elseif($booking->booking_status == 'declined')
                                            <i class="fas fa-ban"></i>
                                        @endif
                                        {{ ucfirst($booking->booking_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div
                                        style="display: flex; justify-content: start; align-items: center; gap: 10px; padding: 8px 0;">
                                        <a href="{{ route('booking.adminShow', $booking->id) }}" class="btn btn-sm"
                                            style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                            data-toggle="tooltip" title="View Booking">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Confirm and Decline Buttons -->
                                        @if ($booking->booking_status === 'pending')
                                            <button type="button" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="modal" data-target="#confirmModal"
                                                data-booking_id="{{ $booking->id }}" data-toggle="tooltip"
                                                title="Confirm Booking">
                                                <i class="fas fa-check-circle"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #dc3545; outline: none;"
                                                data-toggle="modal" data-target="#declineModal"
                                                data-booking_id="{{ $booking->id }}" data-toggle="tooltip"
                                                title="Decline Booking">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif

                                        <!-- Make Project Button -->
                                        @if (
                                            $booking->booking_status === 'confirmed' ||
                                                ($booking->booking_status === 'visited' && $booking->projects->count() < 3))
                                            <a href="{{ route('projects.create', ['booking_id' => $booking->id]) }}"
                                                class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="Create Project">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $bookings->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
    </div>



    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white flex-column align-items-center"
                    style="max-height: 80px; margin-bottom: 10px;">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('arfil_logo1.png') }}" alt="Logo" class="img-fluid logo"
                            style="max-height: 50px; margin-right: 10px;">
                        <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="receipt-details">
                        <p><strong>Name: </strong> <span id="modalName"></span></p>
                        <p><strong>Contact: </strong> <span id="modalContact"></span></p>
                        <p><strong>Email: </strong> <span id="modalEmail"></span></p>
                        <p><strong>Address: </strong> <span id="modalAddress"></span></p>
                        <p><strong>Province: </strong> <span id="modalProvince"></span></p>
                        <p><strong>City: </strong> <span id="modalCity"></span></p>
                        <p><strong>Site Visit Date: </strong> <span id="modalSiteVisitDate"></span></p>
                        <p><strong>Booking Status: </strong> <span id="modalBookingStatus"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to confirm this booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmActionButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Confirmation Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Decline Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to decline this booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="declineActionButton">Decline</button>
                </div>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Show the booking details in the modal when clicking the "View" button
            $('#bookingModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingDetails = {
                    name: button.data('name'),
                    contact: button.data('contact'),
                    email: button.data('email'),
                    address: button.data('address'),
                    province: button.data('province'),
                    city: button.data('city'),
                    siteVisitDate: button.data('site_visit_date'),
                    bookingStatus: button.data('booking_status'),
                    bookingId: button.data('booking_id') // Store booking ID for later use
                };

                const modal = $(this);
                modal.find('#modalName').text(bookingDetails.name);
                modal.find('#modalContact').text(bookingDetails.contact);
                modal.find('#modalEmail').text(bookingDetails.email);
                modal.find('#modalAddress').text(bookingDetails.address);
                modal.find('#modalProvince').text(bookingDetails.province);
                modal.find('#modalCity').text(bookingDetails.city);
                modal.find('#modalSiteVisitDate').text(
                    bookingDetails.siteVisitDate ?
                    new Date(bookingDetails.siteVisitDate).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) :
                    'No site visit scheduled.'
                );

                // Set the booking status badge class and text
                const bookingBadgeClass = getBadgeClass(bookingDetails.bookingStatus);
                modal.find('#modalBookingStatus')
                    .text(capitalizeFirstLetter(bookingDetails.bookingStatus))
                    .removeClass()
                    .addClass('badge ' + bookingBadgeClass);

                // Store the booking ID in the confirm and decline buttons
                $('#confirmActionButton').data('booking_id', bookingDetails.bookingId);
                $('#declineActionButton').data('booking_id', bookingDetails.bookingId);
            });

            // Handle booking confirmation
            $('#confirmModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingId = button.data('booking_id');

                // Set the booking ID for confirmation
                $('#confirmActionButton').data('booking_id', bookingId);
            });

            // Confirm booking action with spinner and SweetAlert
            $('#confirmActionButton').click(function() {
                const bookingId = $(this).data('booking_id');

                $.ajax({
                    url: '/bookings/' + bookingId +
                        '/confirm', // Use the dynamic booking ID in the URL
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    },
                    beforeSend: function() {
                        // Show the spinner or loading indicator
                        $('#confirmActionButton').html(
                            '<span class="spinner-border spinner-border-sm"></span> Confirming...'
                        );
                    },
                    success: function(response) {
                        // Hide spinner and show SweetAlert success message
                        $('#confirmModal').modal('hide');
                        const taskLogs = JSON.parse(localStorage.getItem('taskLogs')) || [];

                        // Add new log to the task logs
                        taskLogs.push({
                            type: response.type, // 'booking' in this case
                            action: response.action, // 'confirm' in this case
                            action_date: response.action_date // The timestamp
                        });

                        // Update localStorage with the new task logs
                        localStorage.setItem('taskLogs', JSON.stringify(taskLogs));

                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmed!',
                            text: response.message,
                            timer: 2000, // Auto close after 2 seconds (2000 milliseconds)
                            timerProgressBar: true,
                            showConfirmButton: false // Hide the confirm button
                        }).then(function() {
                            location.reload(); // Reload the page after confirming
                        });
                    },
                    error: function(xhr) {
                        // Handle the error and show a SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message ||
                                'An error occurred while confirming the booking.',
                        });
                    },
                    complete: function() {
                        // Reset the button text
                        $('#confirmActionButton').html('Confirm');
                    }
                });
            });

            // Handle booking decline
            $('#declineModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingId = button.data('booking_id');

                // Set the booking ID for decline action
                $('#declineActionButton').data('booking_id', bookingId);
            });

            // Decline booking action with spinner and SweetAlert
            $('#declineActionButton').click(function() {
                const bookingId = $(this).data('booking_id');

                $.ajax({
                    url: '/bookings/' + bookingId +
                        '/decline', // Use the dynamic booking ID in the URL
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        // Show spinner or loading indicator
                        $('#declineActionButton').html(
                            '<span class="spinner-border spinner-border-sm"></span> Declining...'
                        );
                    },
                    success: function(response) {
                        // Hide spinner and show SweetAlert success message
                        $('#declineModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Declined!',
                            text: response.message,
                            timer: 2000, // Auto close after 2 seconds
                            timerProgressBar: true,
                            showConfirmButton: false // Hide the confirm button
                        }).then(function() {
                            location.reload(); // Reload the page after declining
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message ||
                                'An error occurred while declining the booking.',
                        });
                    },
                    complete: function() {
                        // Reset the button text
                        $('#declineActionButton').html('Decline');
                    }
                });
            });

            // Function to get badge class based on booking status
            function getBadgeClass(status) {
                switch (status) {
                    case 'pending':
                        return 'badge-warning';
                    case 'confirmed':
                        return 'badge-primary';
                    case 'visited':
                        return 'badge-success';
                    case 'cancelled':
                    case 'declined':
                        return 'badge-danger';
                    default:
                        return 'badge-secondary';
                }
            }

            // Function to capitalize the first letter of a string
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>


@endsection
