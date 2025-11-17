<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <title>Booking</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
        }

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
        }

        .card-header {
            background-color: #17a2b8;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            padding: 20px;
            position: relative;
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
        }

        .btn-transparent {
            background-color: transparent;
            border: 2px solid #17a2b8;
            color: #17a2b8;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
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

        /* Logo Styles */
        .card-header .logo {
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

        .card-header .logo img {
            max-width: 80px;
            height: auto;
        }

        /* Animation Keyframes */
        @keyframes bounceIn {
            0% {
                transform: translateY(-30px) scale(0);
            }

            50% {
                transform: translateY(10px) scale(1.1);
            }

            100% {
                transform: translateY(0) scale(1);
            }
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
    </style>
</head>

<body>

    <div class="backdrop"></div>

    <div class="card shadow-sm border-0 rounded-lg">
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
                    <h2>Booking Successful!</h2>
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

        <div class="card-header text-center">
            <div class="logo">
                <img src="{{ asset('arfil_logo1.png') }}" alt="Logo">
            </div>
            <h4 class="card-title mt-3">Book Your Appointment</h4>
        </div>
        <hr style="border-top: 1px solid #010101; margin: 0;">

        <div class="card-body px-5">
            <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="step active" id="step1">
                    <div class="form-group mb-3">
                        <label for="name">Name <span class="required">*</span></label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email <span class="required">*</span></label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="contact">Contact <span class="required">*</span></label>
                        <input id="contact" type="text" class="form-control @error('contact') is-invalid @enderror"
                            name="contact" required>
                        @error('contact')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Cancel Button -->
                    <div class="button-container mb-3">
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="cancelBtn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="nextBtn">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Address and Date Information -->
                <div class="step" id="step2">
                    <div class="form-group mb-3">
                        <label for="address">Address <span class="required">*</span></label>
                        <input id="address" type="text"
                            class="form-control @error('address') is-invalid @enderror" name="address" required>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <!-- Region -->
                        <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Region</label>
                            <select name="province" class="form-select" id="province" required>
                                <option value="">Select Region</option>
                                @foreach (array_keys($cities) as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City/Municipality -->
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City/Municipality</label>
                            <select name="city" class="form-select" id="city" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="site_visit_date" class="form-label">Site Visit Date <span
                                class="text-danger">*</span></label>
                        <input type="date" name="site_visit_date" id="site_visit_date"
                            class="form-control @error('site_visit_date') is-invalid @enderror"
                            value="{{ old('site_visit_date', \Carbon\Carbon::now()->addWeek()->format('Y-m-d')) }}"
                            required min="{{ \Carbon\Carbon::now()->addWeek()->format('Y-m-d') }}">
                        @error('site_visit_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <!-- Button Container -->
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
                    <h5 class="text-center">Confirm Your Booking</h5>
                    <p>Please review your information:</p>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">Name: <span id="confirmName"></span></li>
                        <li class="list-group-item">Email: <span id="confirmEmail"></span></li>
                        <li class="list-group-item">Contact: <span id="confirmContact"></span></li>
                        <li class="list-group-item">Address: <span id="confirmAddress"></span></li>
                        <li class="list-group-item">Region: <span id="confirmProvince"></span></li>
                        <li class="list-group-item">City: <span id="confirmCity"></span></li>
                        <li class="list-group-item">Date: <span id="confirmDate"></span></li>
                    </ul>

                    <div class="button-container mb-3">
                        <button type="button" class="btn btn-transparent py-2 font-weight-bold" id="prevStepBtn2">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-custom bg-info py-2 font-weight-bold" id="submitBtn">
                            Confirm Booking <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');
            const spinnerOverlay = document.getElementById('spinnerOverlay');

            const steps = document.querySelectorAll('.step');
            let currentStep = 0;

            // Show the current step
            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    step.classList.toggle('active', index === stepIndex);
                });
            }


            // Update confirmation step
            function updateConfirmation() {
                document.getElementById('confirmName').innerText = document.getElementById('name').value;
                document.getElementById('confirmEmail').innerText = document.getElementById('email').value;
                document.getElementById('confirmContact').innerText = document.getElementById('contact').value;
                document.getElementById('confirmAddress').innerText = document.getElementById('address').value;
                document.getElementById('confirmProvince').innerText = document.getElementById('province').value;
                document.getElementById('confirmCity').innerText = document.getElementById('city').value;
                document.getElementById('confirmDate').innerText = document.getElementById('site_visit_date').value;
            }

            // Navigation buttons
            document.getElementById('nextBtn').addEventListener('click', function() {
                showStep(++currentStep);
            });

            document.getElementById('prevBtn').addEventListener('click', function() {
                showStep(--currentStep);
            });

            document.getElementById('nextStepBtn').addEventListener('click', function() {
                showStep(++currentStep);
                updateConfirmation();
            });

            document.getElementById('prevStepBtn2').addEventListener('click', function() {
                showStep(--currentStep);
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            bookingForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Show spinner overlay
                spinnerOverlay.classList.add('active');

                // Prepare the form data
                const formData = new FormData(bookingForm);
                formData.append('user_id', '{{ Auth::id() }}'); // Ensure this is set correctly

                console.log("Sending data to server:", Object.fromEntries(
                    formData)); // Log form data as an object

                // Send AJAX request
                $.ajax({
                    url: bookingForm.action,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log("Success response:", response); // Log success response

                        // Show success message in modal
                        const modalMessage = document.getElementById('modalMessage');
                        modalMessage.innerText = response
                            .message; // Set the message from response

                        const modal = document.getElementById('successModal');
                        modal.style.display = 'block'; // Show the modal

                        // Reset the form and step
                        bookingForm.reset();
                        currentStep = 0; // Reset step
                        showStep(currentStep); // Go back to first step
                        spinnerOverlay.classList.remove('active'); // Hide spinner

                        // Close the modal and redirect after a delay
                        setTimeout(function() {
                            modal.style.display = 'none'; // Hide the modal
                            window.location.href =
                                '{{ route('booking.index') }}'; // Redirect to booking index
                        }, 3000); // 3 seconds delay
                    },
                    error: function(xhr) {
                        console.log("Error response:", xhr); // Log error response
                        const errorMessage = document.getElementById('errorMessages');
                        errorMessage.innerHTML = ''; // Clear previous errors

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            for (const [key, value] of Object.entries(errors)) {
                                // Display error messages in a designated area
                                const errorItem = document.createElement('div');
                                errorItem.textContent = value[0]; // Use the first error message
                                errorMessage.appendChild(
                                    errorItem); // Add to error messages container
                            }
                        } else {
                            const fallbackMessage = document.createElement('div');
                            fallbackMessage.textContent =
                                'An unexpected error occurred.'; // Fallback error message
                            errorMessage.appendChild(
                                fallbackMessage); // Add to error messages container
                        }
                        spinnerOverlay.classList.remove('active'); // Hide spinner
                    }
                });
            });




            document.getElementById('cancelBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to cancel?')) {
                    window.location.href = '/'; // Redirect to homepage
                }
            });
        });

        $(document).ready(function() {
            const cities = @json($cities);

            $('#province').change(function() {
                const selectedRegion = $(this).val();
                const citySelect = $('#city');
                citySelect.empty();
                citySelect.append('<option value="">Select City/Municipality</option>');

                if (selectedRegion) {
                    const provinceCities = cities[selectedRegion] || [];
                    provinceCities.forEach(city => {
                        citySelect.append(`<option value="${city.id}">${city.name}</option>`);
                    });
                }
            });

            const today = new Date();
            const minDate = new Date(today);
            minDate.setDate(today.getDate() + 7); // Set to one week (7 days)

            // Format the date as YYYY-MM-DD
            const dd = String(minDate.getDate()).padStart(2, '0');
            const mm = String(minDate.getMonth() + 1).padStart(2, '0'); // January is 0!
            const yyyy = minDate.getFullYear();
            const formattedMinDate = `${yyyy}-${mm}-${dd}`;

            // Set the min attribute of the input field
            document.getElementById('site_visit_date').setAttribute('min', formattedMinDate);
        });
    </script>
</body>

</html>
