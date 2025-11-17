@extends('layouts.apps')

@section('content')
    <title>Arfil's Landscaping Services</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <div class="card shadow-sm rounded-lg border-1 card-gradient">
        <div class="card-header stylish-header text-black">
            <h1>Rates</h1>
        </div>
        <div class="card-body">
            <!-- Filter and Sort Form -->
            <form action="{{ route('rates.index') }}" method="GET" class="mb-4">
                <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->

                    <!-- Service Type Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                            <select name="service_type" class="form-select form-select-sm" style="max-width: 200px;">
                                <option value="">Service Types</option>
                                <option value="landscaping"
                                    {{ request('service_type') == 'landscaping' ? 'selected' : '' }}>Landscaping</option>
                                <option value="swimmingpool"
                                    {{ request('service_type') == 'swimmingpool' ? 'selected' : '' }}>Swimming Pool</option>
                                <option value="renovation" {{ request('service_type') == 'renovation' ? 'selected' : '' }}>
                                    Renovation</option>
                                <option value="maintenance"
                                    {{ request('service_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="package" {{ request('service_type') == 'package' ? 'selected' : '' }}>Package
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Complexity Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                            <select name="complexity" class="form-select form-select-sm" style="max-width: 150px;">
                                <option value="">Complexities</option>
                                <option value="very_easy" {{ request('complexity') == 'very_easy' ? 'selected' : '' }}>Very
                                    Easy</option>
                                <option value="easy" {{ request('complexity') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ request('complexity') == 'medium' ? 'selected' : '' }}>Medium
                                </option>
                                <option value="hard" {{ request('complexity') == 'hard' ? 'selected' : '' }}>Hard
                                </option>
                                <option value="very_hard" {{ request('complexity') == 'very_hard' ? 'selected' : '' }}>Very
                                    Hard</option>
                            </select>
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

            @if ($rates->isEmpty())
                <p class="text-muted">No rates available at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Service Type</th>
                                <th>Region</th>
                                <th>Complexity</th>
                                <th>Rate (PHP)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rates as $rate)
                                <tr>
                                    <td>{{ ucfirst($rate->service_type) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $rate->region)) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $rate->complexity)) }}</td>
                                    <td>₱{{ number_format($rate->rate, 2) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <!-- Edit Button -->
                                            <a href="javascript:void(0);" class="dropdown-item" data-toggle="tooltip"
                                                title="Edit Rate" onclick="openEditModal({{ $rate->id }})">
                                                <i class="fas fa-edit" style="color: #007bff;"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $rates->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Rate Modal -->
    <div class="modal fade" id="editRateModal" tabindex="-1" role="dialog" aria-labelledby="editRateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRateModalLabel">Edit Rate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editRateForm" action="{{ route('rates.update', 'rate_id') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="service_type">Service Type</label>
                            <input type="text" class="form-control" id="service_type" name="service_type" readonly>
                        </div>
                        <div class="form-group">
                            <label for="region">Region</label>
                            <input type="text" class="form-control" id="region" name="region" readonly>
                        </div>
                        <div class="form-group">
                            <label for="complexity">Complexity</label>
                            <input type="text" class="form-control" id="complexity" name="complexity" readonly>
                        </div>
                        <div class="form-group">
                            <label for="rate">Rate (PHP)</label>
                            <input type="number" class="form-control" id="rate" name="rate" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openEditModal(rateId) {
            // Fetch the rate data using AJAX
            $.ajax({
                url: '/rates/' + rateId + '/edit',
                method: 'GET',
                success: function(response) {
                    // Prefill the modal form fields with the rate data
                    $('#service_type').val(response.service_type);
                    $('#region').val(response.region);
                    $('#complexity').val(response.complexity);
                    $('#rate').val(response.rate);
                    // Set the form action to the correct update route
                    $('#editRateForm').attr('action', '/rates/' + rateId);
                    // Open the modal
                    $('#editRateModal').modal('show');
                }
            });
        }

        $('#editRateForm').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var button = form.find('button[type="submit"]');

            // Change the button text to "Saving changes..."
            button.text('Saving changes...');
            button.prop('disabled', true); // Disable the button to prevent multiple clicks

            // Submit the form using AJAX
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Show a SweetAlert success message for 2 seconds without OK button
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Rate updated successfully.',
                        showConfirmButton: false, // Hide the OK button
                        timer: 2000, // Auto-close after 2 seconds
                    }).then(function() {
                        // After SweetAlert closes, reload the page to show the updated rates
                        window.location.href =
                            "{{ route('rates.index') }}"; // Redirect to the rates page
                    });
                },
                error: function(xhr) {
                    // Handle errors, re-enable the button and reset the text
                    button.text('Save changes');
                    button.prop('disabled', false);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the rate.',
                    });
                }
            });
        });
    </script>
@endsection
