@extends('layouts.apps')

@section('title', 'Make Payment')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* General Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background-color: #ffffff;
        }

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
            z-index: 1;
        }

        .card-header h6 {
            position: relative;
            z-index: 2;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            font-size: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #80bdff;
        }

        .btn {
            border-radius: 50rem !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header">
                <h6 class="mb-0">Make Payment for Project #{{ $project->id }}</h6>
            </div>

            <div class="card-body p-4">
                <!-- Payment Form -->
                <form id="paymentForm" action="{{ route('payment.store', ['projectId' => $project->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <!-- Payment Type -->
                    <div class="mb-4">
                        <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                        <select name="payment_type_display" class="form-select" id="payment_type_display" disabled>
                            <option value="">Select a payment type</option>
                            @php
                                // Logic to determine the payment type
                                $total_paid = $project->total_paid;
                                $total_cost = $project->total_cost;
                                $payment_type = '';

                                if ($total_paid == 0) {
                                    $payment_type = 'initial';
                                } elseif ($total_paid > $total_cost * 0.5 && $total_paid <= $total_cost * 0.8) {
                                    $payment_type = 'midterm';
                                } elseif ($total_paid > $total_cost * 0.8) {
                                    $payment_type = 'final';
                                }
                            @endphp
                            <option value="initial" {{ $payment_type == 'initial' ? 'selected' : '' }}>Initial</option>
                            <option value="midterm" {{ $payment_type == 'midterm' ? 'selected' : '' }}>Midterm</option>
                            <option value="final" {{ $payment_type == 'final' ? 'selected' : '' }}>Final</option>
                        </select>

                        <!-- Hidden input to send the payment_type -->
                        <input type="hidden" name="payment_type" value="{{ $payment_type }}">
                    </div>



                    <!-- Payment Method -->
                    <div class="mb-4">
                        <label for="payment_method" class="form-label">Payment Method <span
                                class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" id="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="gcash">Gcash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Remaining Payables -->
                    <div class="mb-4">
                        <h6>Remaining Payables: <span class="text-success">PHP
                                {{ number_format($project->total_cost - $project->total_paid, 2) }}</span></h6>
                    </div>

                    <!-- Amount -->
                    <div class="mb-4">
                        <label for="amount" class="form-label">Amount (PHP) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount"
                            required min="{{ $project->total_paid == 0 ? $project->total_cost * 0.5 : 0 }}"
                            max="{{ $project->total_cost - $project->total_paid }}" step="0.01">
                    </div>


                    <!-- Payment Proof -->
                    <div class="mb-4">
                        <label for="payment_image" class="form-label">Payment Proof (Image)</label>
                        <input type="file" name="payment_image" id="payment_image" class="form-control" accept="image/*">
                    </div>

                    <!-- Remarks -->
                    <div class="mb-4">
                        <label for="remarks" class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter any remarks"></textarea>
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" id="submitButton" class="btn btn-sm btn-info px-4 py-2">
                            <i class="fas fa-credit-card me-1"></i> Submit Payment
                        </button>
                        <a href="{{ route('project.adminIndex') }}" class="btn btn-sm btn-secondary px-4 py-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#paymentForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
    
                // Change button text to "Submitting Payment..."
                $('#submitButton').html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting Payment...');
    
                $.ajax({
                    url: $(this).attr('action'), // Get the action URL from the form
                    method: 'POST',
                    data: new FormData(this), // Serialize the form data, including files
                    contentType: false, // Set content type to false to handle FormData correctly
                    processData: false, // Prevent jQuery from automatically transforming the data into a query string
                    success: function(response) {
                        // Show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Payment submitted successfully!',
                            icon: 'success',
                            showConfirmButton: false, // Hide confirm button
                            timer: 2000, // Show alert for 2 seconds
                            timerProgressBar: true // Optional: Show timer progress bar
                        });
    
                        // Redirect after 2 seconds
                        setTimeout(function() {
                            window.location.href = "{{ route('project.adminIndex') }}"; // Redirect to the desired route
                        }, 2000); // 2000 milliseconds = 2 seconds
    
                        // Optionally, you can clear the form
                        $('#paymentForm')[0].reset(); // Reset the form
    
                        // Reset button text back to "Submit Payment"
                        $('#submitButton').html('<i class="fas fa-credit-card me-1"></i> Submit Payment');
                    },
                    error: function(xhr, status, error) {
                        // Handle error if necessary
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error processing your request.',
                            icon: 'error',
                            showConfirmButton: false, // Hide confirm button
                            timer: 2000, // Show alert for 2 seconds
                            timerProgressBar: true // Optional: Show timer progress bar
                        });
    
                        // Reset button text back to "Submit Payment" if there was an error
                        $('#submitButton').html('<i class="fas fa-credit-card me-1"></i> Submit Payment');
                    }
                });
            });
        });
    </script>

@endsection
