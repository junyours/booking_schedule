@extends('layouts.apps')

@section('title')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* General Styles */
        .card {
            border: none;
            border-radius: 12px;
            /* Remove or override the overflow property */
            /* overflow: hidden; */
            /* Commented out to prevent clipping */
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background-color: #ffffff;
        }

        /* If you still need overflow: hidden for other reasons, consider overriding it here */
        /* .card {
                                                                                                                                                                                                overflow: visible;
                                                                                                                                                                                            } */

        .card-header {
            position: relative;
            width: 100%;
            height: 180px;
            background-image: url('{{ asset('background.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Dark overlay for text readability */
            z-index: 1;
        }

        .card-header h6 {
            position: relative;
            z-index: 2;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            /* Center the text */
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
            background-color: #f8f9fa;
        }

        .modal-title {
            font-weight: 600;
            color: #343a40;
        }

        .modal-body {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            /* Responsive columns */
            gap: 20px;
            background-color: #ffffff;
        }

        .design-card {
            cursor: pointer;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .design-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .design-img {
            width: 100%;
            height: 180px;
            /* Adjusted height for better balance */
            object-fit: cover;
        }

        .design-card-content {
            padding: 20px;
            text-align: center;
            flex-grow: 1;
        }

        .design-card-content h5 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #495057;
        }

        /* Button Styles */
        .btn-grey {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-grey:hover {
            background-color: #5a6268;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
        }

        .btn-outline-info:hover {
            background-color: #17a2b8;
            color: #fff;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .px-4 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        /* Form Controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            box-sizing: border-box;
            /* Ensure padding is included in width */
            font-size: 1rem;
            /* Ensure font size is readable */
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #80bdff;
        }

        /* Form Controls */
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            box-sizing: border-box;
            /* Ensure padding is included in width */
            font-size: 1rem;
            /* Ensure font size is readable */
        }


        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
        }

        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent background */
            backdrop-filter: blur(5px);
            /* Blur effect */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9998;
            /* Behind alert message */
        }

        /* Alert Styles */
        #alert-message {
            display: none;
            /* Initially hidden */
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .modal-body {
                grid-template-columns: 1fr;
                /* Single column on smaller screens */
            }

            .card-header {
                height: 150px;
            }

            .design-img {
                height: 150px;
            }

            .card-header h6 {
                font-size: 1.2rem;
            }
        }
    </style>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header">
                <h6 class="mb-0">Add Project for Booking # {{ $booking_id }}</h6>
            </div>

            <div class="card-body p-4">
                <div class="modal fade" id="designModal" tabindex="-1" aria-labelledby="designModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="designModalLabel">Choose Services</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="designsContainer">
                                <!-- Design cards will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="alert-message" class="alert alert-dismissible fade show" role="alert" style="display:none;">
                    <span class="icon">
                        <i id="alert-icon" class="fas"></i>
                    </span>
                    <span id="alert-text"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <form id="project-form" action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <!-- Lot Area -->
                    <div class="mb-4">
                        <label for="lot_area" class="form-label">Lot Area (sqm) <span class="text-danger">*</span></label>
                        <input placeholder="min 20 - max 300" name="lot_area" id="lot_area"
                            class="form-control @error('lot_area') is-invalid @enderror" value="{{ old('lot_area') }}"
                            min="20" max="300" step="0.01" required>
                        @error('lot_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>




                    <!-- Hidden Input for Service IDs -->
                    <input type="hidden" name="service_ids" id="service_ids">

                    <div class="mb-4">
                        <label for="cost" class="form-label">Cost (PHP) <span class="text-danger">*</span></label>
                        <input type="number" name="cost" id="cost" class="form-control" placeholder="Enter cost"
                            required min="0" step="0.01">
                    </div>

                    <!-- Discount -->
                    <div class="mb-4">
                        <label for="discount" class="form-label">Discount<span class="text-danger">*</span></label>
                        <select name="discount" class="form-select" id="discount">
                            <option value="">Select a discount</option>
                            @foreach ($discounts as $discount)
                                <option value="{{ $discount }}">{{ $discount }}%</option>
                            @endforeach
                        </select>
                    </div>



                    <!-- Start Date -->
                    <div class="mb-4">
                        <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', \Carbon\Carbon::now()->addWeek()->format('Y-m-d')) }}" required
                            min="{{ \Carbon\Carbon::now()->addWeek()->format('Y-m-d') }}">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Category Selection -->
                    <div class="form-group mb-4">
                        <label for="category" class="form-label">Category</label>
                        <span class="text-danger">*</span>
                        <select name="category" class="form-select" id="category" required>
                            <option value="">Select a category</option>
                            <option value="landscaping" {{ old('category') == 'landscaping' ? 'selected' : '' }}>
                                Landscaping
                            </option>
                            <option value="swimmingpool" {{ old('category') == 'swimmingpool' ? 'selected' : '' }}>
                                Swimming
                                Pool</option>
                            <option value="renovation" {{ old('category') == 'renovation' ? 'selected' : '' }}>Renovation
                            </option>
                            <option value="package" {{ old('category') == 'package' ? 'selected' : '' }}>Package</option>
                            <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>
                                Maintenance
                            </option>
                        </select>
                    </div>
                    <!-- Selected Services Display -->
                    <div class="text-center mb-4">
                        <strong>Selected Services:</strong>
                        <ul id="selectedServicesList" class="list-unstyled">
                            <!-- Selected services will be dynamically populated here -->
                        </ul>
                    </div>


                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-outline-secondary" id="addDesignButton"
                            data-bs-toggle="modal" data-bs-target="#designModal">
                            <i class="fas fa-plus me-2"></i> Choose Services
                        </button>
                    </div>



                    <!-- Save and Cancel Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-sm btn-info me-2 rounded-pill px-4 py-2"
                            id="saveProjectButton">
                            <i class="fas fa-save me-1"></i>
                            <span class="button-text">Save Project</span>
                            <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"
                                style="display: none;"></span>
                        </button>

                        <a href="{{ route('project.adminIndex') }}"
                            class="btn btn-sm btn-secondary me-2 rounded-pill px-4 py-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        < script src = "https://cdn.jsdelivr.net/npm/sweetalert2@11" >
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Array to hold selected services
            let selectedServices = [];

            // Event listener for input changes
            $('#category, #complexity, #lot_area, #discount').on('change input', function() {
                const selectedCategory = $('#category').val();
                const lotArea = parseFloat($('#lot_area').val()) || 0;
                const cost = parseFloat($('#cost').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;

                if (selectedCategory && lotArea > 0) {
                    $.ajax({
                        url: '/calculate-cost',
                        type: 'POST',
                        data: {
                            services: selectedServices,
                            lot_area: lotArea,
                            discount: discount,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#costValue').text('₱' + parseFloat(response.cost).toFixed(2));
                            const totalCost = cost - (cost * (discount / 100));
                            $('#total_cost').val('₱' + totalCost.toFixed(2));
                        },
                        error: function() {
                            resetCostDisplay();
                        }
                    });
                } else {
                    resetCostDisplay();
                }
            });

            function resetCostDisplay() {
                $('#costValue').text('₱0.00');
                $('#total_cost').val('₱0.00');
            }

            $('#project-form').on('submit', function(event) {
                event.preventDefault();

                const $submitButton = $('#saveProjectButton');
                if ($submitButton.prop('disabled')) return;

                if (selectedServices.length === 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'At least one service is required.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let discount = $('#discount').val() || 0;
                const firstServiceId = selectedServices[0].id;

                // Change button text to 'Saving Project...' and show spinner
                $submitButton.prop('disabled', true);
                $submitButton.find('.button-text').text('Saving Project...');
                $submitButton.find('.spinner-border').show(); // Show the spinner

                const formData = $(this).serialize() +
                    '&start_date=' + $('#start_date').val() +
                    '&discount=' + discount +
                    '&service_id=' + firstServiceId +
                    '&service_ids[]=' + selectedServices.map(service => service.id).join(
                    '&service_ids[]=') +
                    '&services=' + JSON.stringify(selectedServices);

                $('#spinner')
            .show(); // Assuming this is a different spinner, if not, you can remove this line

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show SweetAlert for project creation success
                        Swal.fire({
                            title: 'Success!',
                            text: response.message || 'Project saved successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });

                        if (response.cost) {
                            $('#costValue').text('₱' + parseFloat(response.cost).toFixed(2));
                        }
                        setTimeout(() => {
                            window.location.href = "{{ route('project.adminIndex') }}";
                        }, 3000);
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                        if (errors) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Please fix the errors below.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }

                        // Reset button text and hide spinner
                        $submitButton.prop('disabled', false);
                        $submitButton.find('.button-text').text('Save Project');
                        $submitButton.find('.spinner-border').hide(); // Hide the spinner
                        $('#spinner')
                    .hide(); // Assuming this is a different spinner, if not, you can remove this line
                    }
                });
            });



            // Handle Add Design Button Click
            $('#addDesignButton').on('click', function() {
                const selectedCategory = $('#category').val();
                if (!selectedCategory) return;

                $.ajax({
                    url: '/designs/' + selectedCategory,
                    type: 'GET',
                    success: function(response) {
                        const designsContainer = $('#designsContainer');
                        designsContainer.empty();

                        if (Array.isArray(response) && response.length) {
                            response.forEach(design => {
                                designsContainer.append(`
                                <div class="design-card" data-id="${design.id}" data-name="${design.name}" data-complexity="${design.complexity}">
                                    <img src="${design.design}" class="design-img" alt="${design.name}">
                                    <div class="design-card-content">
                                        <h5>${design.name}</h5>
                                        <p>${design.description}</p>
                                        <p><strong>Complexity:</strong> ${design.complexity}</p>
                                    </div>
                                    <button class="select-design-btn btn btn-info">Select</button>
                                </div>
                            `);
                            });

                            var designModal = new bootstrap.Modal(document.getElementById(
                                'designModal'));
                            designModal.show();

                            // Attach click event to select design buttons
                            $('.select-design-btn').off('click').on('click', function() {
                                const card = $(this).closest('.design-card');
                                const id = card.data('id');
                                const name = card.data('name');
                                const complexity = card.data('complexity');

                                if (!selectedServices.some(service => service.id ===
                                        id)) {
                                    selectedServices.push({
                                        id,
                                        name,
                                        complexity
                                    });

                                    $('#service_id').val(selectedServices.map(service =>
                                        service.id).join(','));
                                    updateSelectedServicesDisplay();

                                    var designModal = bootstrap.Modal.getInstance(
                                        document.getElementById('designModal'));
                                    if (designModal) {
                                        designModal.hide();
                                    }
                                    // Cleanup
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open').css(
                                        'padding-right', '');
                                } else {
                                    alert('Service already selected!');
                                }
                            });
                        } else {
                            designsContainer.html('<p>No designs available.</p>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching designs:', xhr.responseText);
                    }
                });
            });

            // Function to update the display of selected services
            function updateSelectedServicesDisplay() {
                const $selectedServicesList = $('#selectedServicesList');
                $selectedServicesList.empty();

                selectedServices.forEach(service => {
                    $selectedServicesList.append(
                        `<li class="d-flex justify-content-between align-items-center mb-2">
                <span class="mx-auto text-center">${service.name}</span> <!-- Center the service name -->
                <button type="button" class="btn btn-link text-danger" onclick="removeService(${service.id})">
                    <i class="fas fa-times"></i> <!-- X icon -->
                </button>
            </li>`
                    );
                });
            }

            // Function to remove a service by ID
            window.removeService = function(serviceId) {
                // Remove the service from the selectedServices array
                selectedServices = selectedServices.filter(service => service.id !== serviceId);

                // Update the display
                updateSelectedServicesDisplay();

                // Update the hidden input for form submission
                $('#service_id').val(selectedServices.map(service => service.id).join(','));
            };


        });
    </script>






@endsection
