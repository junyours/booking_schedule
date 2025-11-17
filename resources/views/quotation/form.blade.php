<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arfil's Landscaping Services</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Global Styles */
        body {
            background: url('{{ asset('1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            margin: 0;
        }

        /* Backdrop */
        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            z-index: 1;
            animation: fadeIn 1s ease-in-out;
        }

        /* Card Styles */
        .card {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 2;
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: visible;
        }

        .card-header {
            background-color: #17a2b8;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            padding: 40px 20px 20px;
            /* Increased top padding */
            position: relative;
        }

        .logo {
            position: absolute;
            top: -65px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: bounceIn 1s ease;
        }

        .logo img {
            max-width: 80px;
            height: auto;
        }

        @keyframes bounceIn {
            0% {
                transform: translateY(-50px) scale(0);
                /* Start above and scaled down */
            }

            50% {
                transform: translateY(10px) scale(1.1);
                /* Move down and scale up */
            }

            100% {
                transform: translateY(0) scale(1);
                /* End at final position */
            }
        }



        .card-header .logo img {
            max-width: 80px;
            height: auto;
        }

        .form-control {
            border-radius: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 10px rgba(23, 162, 184, 0.5);
        }

        .btn-custom {
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 10px;
            color: white;
            width: 100%;
            padding: 10px;
            background-color: #17a2b8;
        }

        .btn-custom:hover {
            background-color: #138496;
            transform: scale(1.05);
        }

        .btn-transparent {
            background-color: transparent;
            border: 2px solid #17a2b8;
            color: #17a2b8;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
            border-radius: 10px;
            padding: 10px;
        }

        .btn-transparent:hover {
            background-color: #17a2b8;
            color: white;
            transform: scale(1.05);
        }

        .invalid-feedback {
            color: #e3342f;
        }

        label {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .required {
            color: red;
        }

        /* Spinner Overlay */
        .spinner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            border-radius: 15px;
        }

        .spinner-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }


        /* Step Navigation */
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            flex: 1;
        }

        /* Modals */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5);
            /* Black w/ opacity */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 15px;
            width: 320px;
            /* Increased width to avoid cutting off the icon */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        .modal-header,
        .modal-body {
            text-align: center;
        }

        .modal-icon {
            margin-bottom: 10px;
        }

        .box00 {
            width: 120px;
            /* Adjusted size */
            height: 120px;
            /* Adjusted size */
            margin: 0 auto;
        }

        .path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 0.5s ease;
            /* Smooth transition for animation */
        }

        .path.circle {
            animation: dash 0.9s ease-in-out forwards;
        }

        .path.line {
            stroke-dashoffset: 1000;
            animation: dash 0.95s 0.35s ease-in-out forwards;
        }

        /* Animation keyframes */
        @keyframes dash {
            from {
                stroke-dashoffset: 1000;
            }

            to {
                stroke-dashoffset: 0;
            }
        }

        .close-button {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }

        .close-button:hover {
            color: #000;
        }

        .modal-header h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-body {
            text-align: center;
        }

        .modal-icon {
            margin-bottom: 15px;
        }

        /* Design Selection Modal */
        .design-selection {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
            /* Prevent scrollbars on the modal */
        }

        .design-selection.active {
            display: flex;
        }

        .design-selection-content {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 800px;
            max-height: 90vh;
            /* Set a maximum height for the modal */
            overflow: hidden;
            /* Hide overflow for content */
            position: relative;
        }

        /* Body of the modal where designs are displayed */
        .design-modal-body {
            max-height: 80vh;
            /* Set a max-height for the body */
            overflow-y: scroll;
            /* Enable vertical scrolling */
            padding-right: 15px;
            /* Add space for scrollbar width */
        }

        /* Hide scrollbar styles */
        .design-modal-body::-webkit-scrollbar {
            width: 0;
            /* Hide scrollbar for Chrome, Safari, and Opera */
        }

        .design-modal-body {
            -ms-overflow-style: none;
            /* Hide scrollbar for Internet Explorer and Edge */
            scrollbar-width: none;
            /* Hide scrollbar for Firefox */
        }

        /* Container for the design cards */
        .designs-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            /* Space between cards */
            padding: 10px;
            /* Padding around the cards */
        }

        /* Individual service card styles */
        .service-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            width: calc(48% - 15px);
            /* Adjust width for responsiveness */
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }



        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .service-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .service-card h5 {
            margin: 15px 0 5px;
            font-weight: bold;
        }

        .service-card p {
            margin: 5px 15px 10px;
            flex-grow: 1;
        }

        .service-card .complexity {
            margin-top: 10px;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .service-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="backdrop"></div>

    <div class="card shadow-sm border-0 rounded-lg">
        <!-- Spinner Overlay -->
        <div class="spinner-overlay" id="spinnerOverlay">
            <div class="d-flex flex-column align-items-center">
                <div class="spinner-grow text-info" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="text-info mt-2">Please Wait...</span>
            </div>
        </div>

        <div id="successModal" class="modal">
            <div class="modal-content">
                <span class="close-button">&times;</span>
                <div class="modal-header">
                    <h2>Quotation Sent!</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-icon box00">
                        <svg class="icon-animate" viewBox="0 0 100 100">
                            <circle class="path circle" cx="50" cy="50" r="45" fill="none"
                                stroke="#4CAF50" stroke-width="5" />
                            <line class="path line" x1="30" y1="50" x2="45" y2="65"
                                stroke="#4CAF50" stroke-width="5" />
                            <line class="path check" x1="45" y1="65" x2="70" y2="35"
                                stroke="#4CAF50" stroke-width="5" />
                        </svg>
                    </div>
                    <p id="modalMessage"></p>
                </div>
            </div>
        </div>

        <!-- Add Design Modal -->
        <div id="addDesignModal" class="design-selection">
            <div class="design-selection-content">
                <span class="close-button">&times;</span>
                <h2>Select a Design</h2>
                <div class="design-modal-body">
                    <div class="designs-container" id="designsContainer">
                        <!-- This will be populated dynamically -->
                        <div class="service-card">
                            <img src="path/to/image1.jpg" alt="Design 1">
                            <h5>Design 1</h5>
                            <p>Description for Design 1.</p>
                        </div>
                        <div class="service-card">
                            <img src="path/to/image2.jpg" alt="Design 2">
                            <h5>Design 2</h5>
                            <p>Description for Design 2.</p>
                        </div>
                        <!-- Add more cards as needed -->
                    </div>
                </div>
            </div>
        </div>


        <div class="card-header text-center">
            <div class="logo">
                <img src="{{ asset('arfil_logo1.png') }}" alt="Logo">
            </div>
            <h4 class="card-title mt-3">Make Quotation</h4>
        </div>


        <hr style="border-top: 1px solid #010101; margin: 0;">

        <!-- Card Body with Form -->
        <div class="card-body px-5">
            <form method="POST" action="{{ route('quotation.store') }}" id="quotationForm">
                @csrf

                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="step active" id="step1">
                    <div class="form-group mb-3">
                        <label for="address">Address <span class="required">*</span></label>
                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"
                            name="address" required>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <!-- Region -->
                        <div class="col-md-6 mb-3">
                            <label for="region" class="form-label">Region <span class="required">*</span></label>
                            <select name="region" class="form-select @error('region') is-invalid @enderror"
                                id="region" required>
                                id="region" required>
                                <option value="">Select Region</option>
                                @foreach (array_keys($cities) as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                            @error('region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- City/Municipality -->
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City/Municipality <span
                                    class="required">*</span></label>
                            <select name="city" class="form-select @error('city') is-invalid @enderror"
                                id="city" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="button-container mb-3">
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="cancelBtn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="nextBtn">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Service and Lot Area Information -->
                <div class="step" id="step2">
                    <div class="form-group mb-3">
                        <label for="lot_area">Lot Area (sqm) <span class="required">*</span></label>
                        <input id="lot_area" type="number" min="20" max="300"
                            class="form-control @error('lot_area') is-invalid @enderror" name="lot_area" required>
                        @error('lot_area')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Service Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="service" class="form-label">Service <span class="required">*</span></label>
                            <select name="service" class="form-select @error('service') is-invalid @enderror"
                                id="service" required>
                                <option value="">Select a Service</option>
                                <option value="landscaping">Landscaping</option>
                                <option value="swimmingpool">Swimming Pool</option>
                                <option value="renovation">Renovation</option>
                                <option value="package">Package</option>

                            </select>
                            @error('service')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Selected Service Name Display -->
                    <div class="row mb-3">
                        <label class="mt-2">Selected Service: <span id="selectedServiceName">None</span></label>
                    </div>

                    <!-- Hidden Service ID Input -->
                    <input type="hidden" id="serviceIdInput" name="service_id" value="">

                    <!-- Add Design Button -->
                    <div class="button-container mb-3">
                        <button type="button" class="btn btn-info py-2 font-weight-bold" id="addDesignBtn">
                            Add Design <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="button-container mb-3">
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="prevBtn">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="nextStepBtn">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Confirmation -->
                <div class="step" id="step3">
                    <h5 class="text-center">Review Your Quotation</h5>
                    <p>Please review your information:</p>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">Address: <span id="confirmAddress"></span></li>
                        <li class="list-group-item">Region: <span id="confirmProvince"></span></li>
                        <li class="list-group-item">City: <span id="confirmCity"></span></li>
                        <li class="list-group-item">Lot Area: <span id="confirmLotArea"></span> sqm</li>
                        <li class="list-group-item">Service: <span id="confirmService"></span></li>
                        <li class="list-group-item">Selected Service: <span id="confirmDesign"></span></li>
                    </ul>

                    <div class="button-container mb-3">
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="prevStepBtn2">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-custom bg-info py-2 font-weight-bold" id="submitBtn">
                            Submit Quotation <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const quotationForm = $('#quotationForm');
            const spinnerOverlay = $('#spinnerOverlay');
            const successModal = $('#successModal');
            const addDesignModal = $('#addDesignModal');
            const designsContainer = $('#designsContainer');
            const selectedServiceName = $('#selectedServiceName');
            const serviceIdInput = $('#serviceIdInput');

            const steps = $('.step');
            let currentStep = 0;

            // Function to show the current step
            function showStep(stepIndex) {
                steps.removeClass('active');
                $(steps[stepIndex]).addClass('active');
            }

            // Initialize first step
            showStep(currentStep);

            // Handle Next button in Step 1
            $('#nextBtn').click(function() {
                if (validateStep1()) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            // Handle Previous button in Step 2
            $('#prevBtn').click(function() {
                currentStep--;
                showStep(currentStep);
            });

            // Handle Next button in Step 2
            $('#nextStepBtn').click(function() {
                if (validateStep2()) {
                    updateConfirmation();
                    currentStep++;
                    showStep(currentStep);
                }
            });

            // Handle Previous button in Step 3
            $('#prevStepBtn2').click(function() {
                currentStep--;
                showStep(currentStep);
            });

            // Handle Cancel button
            $('#cancelBtn').click(function() {
                if (confirm('Are you sure you want to cancel?')) {
                    window.location.href = "{{ route('quotation.view') }}";
                }
            });

            // Validate Step 1
            function validateStep1() {
                const address = $('#address').val().trim();
                const region = $('#region').val();
                const city = $('#city').val();

                if (!address || !region || !city) {
                    alert('Please fill out all required fields in Step 1.');
                    return false;
                }

                return true;
            }

            // Validate Step 2
            function validateStep2() {
                const lotArea = $('#lot_area').val();
                const service = $('#service').val();

                if (!lotArea || !service) {
                    alert('Please fill out all required fields in Step 2.');
                    return false;
                }

                if (lotArea < 20 || lotArea > 300) {
                    alert('Please enter a lot area between 20 and 300 square meters.');
                    return false;
                }

                return true;
            }

            // Update Confirmation Step
            function updateConfirmation() {
                $('#confirmAddress').text($('#address').val());
                $('#confirmProvince').text($('#region').val());
                $('#confirmCity').text($('#city').val());
                $('#confirmLotArea').text($('#lot_area').val());
                $('#confirmService').text($('#service').val());
                $('#confirmDesign').text(selectedServiceName.text() || 'None');
            }

            // Handle Add Design Button
            $('#addDesignBtn').click(function() {
                const selectedService = $('#service').val();

                if (!selectedService) {
                    alert('Please select a service before adding a design.');
                    return;
                }

                // Fetch designs based on selected service
                $.ajax({
                    url: `/designs/${selectedService}`,
                    method: 'GET',
                    success: function(data) {
                        if (Array.isArray(data)) {
                            let designsHtml = '';
                            data.forEach(design => {
                                designsHtml += `
                                    <div class="service-card" data-id="${design.id}" data-name="${design.name}">
                                        <img src="${design.design}" alt="${design.name}">
                                        <h5>${design.name}</h5>
                                        <p>${design.description}</p>
                                        <p class="complexity"><strong>Complexity:</strong> ${design.complexity}</p>
                                    </div>
                                `;
                            });
                            designsContainer.html(designsHtml);

                            // Add click event to each design card
                            $('.service-card').click(function() {
                                const id = $(this).data('id');
                                const name = $(this).data('name');

                                // Set selected design
                                serviceIdInput.val(id);
                                selectedServiceName.text(name);

                                // Close modal
                                addDesignModal.removeClass('active');
                            });

                            // Show the design selection modal
                            addDesignModal.addClass('active');
                        } else {
                            designsContainer.html(
                                `<p>${data.error || 'No designs available.'}</p>`);
                            addDesignModal.addClass('active');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch designs. Please try again later.');
                    }
                });
            });

            // Close buttons for modals
            $('.close-button').click(function() {
                $(this).closest('.modal').hide();
                addDesignModal.removeClass('active');
            });

            // Handle Form Submission
            quotationForm.submit(function(event) {
                event.preventDefault();

                // Show spinner
                spinnerOverlay.addClass('active');

                // Prepare form data
                const formData = $(this).serialize();

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        spinnerOverlay.removeClass('active');

                        // Show success modal
                        $('#modalMessage').text(response.message ||
                            'Your quotation has been submitted successfully!');
                        successModal.show();

                        // Reset form and step
                        quotationForm[0].reset();
                        selectedServiceName.text('None');
                        serviceIdInput.val('');
                        currentStep = 0;
                        showStep(currentStep);

                        // Redirect after a delay
                        setTimeout(function() {
                            successModal.hide();
                            window.location.href = '{{ route('quotation.view') }}';
                        }, 3000);
                    },
                    error: function(xhr) {
                        spinnerOverlay.removeClass('active');
                        let errorMsg = 'An unexpected error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join(' ');
                        }
                        alert(errorMsg);
                    }
                });
            });

            // Populate Cities based on Province
            const cities = @json($cities);

            $('#region').change(function() {
                const selectedRegion = $(this).val();
                const citySelect = $('#city');
                citySelect.empty().append('<option value="">Select City/Municipality</option>');

                if (selectedRegion && cities[selectedRegion]) {
                    cities[selectedRegion].forEach(city => {
                        citySelect.append(`<option value="${city.name}">${city.name}</option>`);
                    });
                }
            });
        });
    </script>
</body>

</html>
