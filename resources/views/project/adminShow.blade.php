@extends('layouts.apps')
@php
    use App\Models\Service;
    $latestPayment = $projects->payments()->latest()->first();

@endphp
@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-light" id="printableArea">
            <div class="card-header text-white bg-info text-center d-flex flex-column align-items-center">
                <img src="{{ asset('arfil_logo.png') }}" alt="Company Logo" class="logo" style="width: 150px">
                <h2 class="mb-1">Arfils Landscaping Services</h2>
                <p class="mb-0">Professional Landscaping & Pool Services</p>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h4 class="mb-0">Project Details</h4>
                    <h3 class="mb-0" style="font-size: 16px;">Created:
                        {{ \Carbon\Carbon::parse($projects->booking->created_at)->format('F j, Y') }}</h3>
                </div>

                <table class="table table-sm">
                    <tbody>

                        <tr>
                            <th>Booking ID</th>
                            <td>{{ $projects->booking->id }}</td>
                        </tr>
                        <tr>
                            <th>Client Name</th>
                            <td>{{ $projects->booking->name }}</td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>
                                @php
                                    $serviceIds = $projects->service_ids ? json_decode($projects->service_ids) : [];
                                    $services = !empty($serviceIds)
                                        ? \App\Models\Service::whereIn('id', $serviceIds)->get()
                                        : collect();
                                @endphp

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
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $projects->booking->address }}</td>
                        </tr>
                        <tr>
                            <th>Province</th>
                            <td>{{ $projects->booking->province }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $projects->booking->city }}</td>
                        </tr>
                        <tr>
                            <th>Lot Area</th>
                            <td>{{ $projects->lot_area }} sqm</td>
                        </tr>
                        <tr>
                            <th>Cost</th>
                            <td>₱{{ number_format($projects->cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>{{ $projects->discount }}%</td>
                        </tr>
                        <tr>
                            <th>Total Cost</th>
                            <td class="d-flex justify-content-between align-items-center">
                                <span>₱{{ number_format($projects->total_cost, 2) }}</span>

                            </td>


                        </tr>


                        <tr>
                            <th>Total Paid</th>
                            <td class="d-flex justify-content-between align-items-center">
                                <span>₱{{ number_format($projects->total_paid, 2) }}</span>
                                <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                    data-bs-target="#paymentImagesModal" data-images='@json($projects->payments->pluck('payment_image'))'
                                    data-amounts='@json($projects->payments->pluck('amount'))'>
                                    <i class="fas fa-eye fa-lg" data-bs-toggle="tooltip" title="Show all payments"></i>
                                    <!-- Eye icon to indicate "View" -->
                                </button>

                            </td>

                        </tr>

                        <tr>
                            <th>Payables</th>
                            <td class="d-flex justify-content-between align-items-center">
                                @php
                                    $payables = $projects->total_cost - $projects->total_paid;
                                @endphp
                                <span
                                    class="d-flex align-items-center {{ $payables <= 0 ? 'text-success' : 'text-danger' }}">
                                    @if ($payables <= 0)
                                        <i class="fas fa-check-circle text-success fa-lg me-2"></i>
                                        <!-- Check icon for completed -->
                                        Paid completed
                                    @else
                                        <i class="fas fa-exclamation-circle text-danger fa-lg me-2"></i>
                                        <!-- Exclamation icon for pending -->
                                        ₱{{ number_format($payables, 2) }}
                                    @endif
                                </span>
                                @if ($payables > 0 && $projects->total_cost > $projects->total_paid && $projects->project_status != 'cancel')
                                    <a href="{{ route('payment.payment', ['projectId' => $projects->id]) }}"
                                        class="btn btn-link">
                                        <i class="fas fa-credit-card fa-lg" data-bs-toggle="tooltip"
                                            title="Make a Payment"></i> <!-- Payment icon -->
                                    </a>
                                @endif
                            </td>
                        </tr>





                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge 
                                    @if ($projects->project_status == 'pending') badge-warning 
                                    @elseif($projects->project_status == 'active') badge-success 
                                    @elseif($projects->project_status == 'hold') badge-danger    
                                    @elseif($projects->project_status == 'finish') badge-primary
                                    @elseif($projects->project_status == 'cancel') badge-secondary @endif">

                                    @if ($projects->project_status == 'pending')
                                        <i class="fas fa-hourglass-half"></i>
                                    @elseif($projects->project_status == 'active')
                                        <i class="fas fa-spinner fa-spin"></i>
                                    @elseif($projects->project_status == 'hold')
                                        <i class="fas fa-pause-circle"></i>
                                    @elseif($projects->project_status == 'finish')
                                        <i class="fas fa-check"></i>
                                    @elseif($projects->project_status == 'cancel')
                                        <i class="fas fa-times-circle"></i> <!-- Icon for cancel -->
                                    @endif

                                    {{ ucfirst($projects->project_status) }}
                                </span>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <hr class="my-4">

                <div class="mt-4">
                    <h4>Project Progress</h4>
                    <div class="progress">
                        @php
                            $latestProgress = $projects->progress->last();
                            $progress = $latestProgress ? $latestProgress->phase_progress : 0; // Fallback to 0 if no progress
                            $currentPhase = $latestProgress ? $latestProgress->phase : 'phase_one'; // Default to 'phase_one' if no phase

                            $phases = [
                                'phase_one' => 'Phase One',
                                'phase_two' => 'Phase Two',
                                'phase_three' => 'Phase Three',
                            ];

                            // Display current phase and progress without auto-advancing
                            $readablePhase = $phases[$currentPhase] ?? 'Not Started';
                            $phaseColor = 'bg-success'; // Default color for success
                            if ($progress < 50) {
                                $phaseColor = 'bg-warning'; // Color for warning
                            } elseif ($progress == 100) {
                                $phaseColor = 'bg-primary'; // Color for complete
                            }
                        @endphp

                        <div class="progress-bar {{ $phaseColor }}" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>

                    <div class="mt-2 text-center">
                        <i class="fas fa-check-circle"></i>
                        @if ($progress == 100 && $currentPhase === 'phase_three')
                            Project Complete
                        @elseif ($progress == 100)
                            {{ $readablePhase }} (Complete)
                        @elseif ($progress == 0 && ($currentPhase === 'phase_two' || $currentPhase === 'phase_three'))
                            {{ $readablePhase }} (Starting)
                        @elseif ($progress > 0)
                            {{ $readablePhase }} (In Progress)
                        @else
                            Project Not Started
                        @endif
                    </div>
                </div>


                @if ($projects->project_status !== 'pending')
                    <div class="text-center mt-2">
                        <a href="{{ route('progress.index', ['projectId' => $projects->id]) }}" class="btn btn-link">
                            <i class="fas fa-arrow-right me-1"></i> <!-- Add your desired icon here -->
                            View More
                        </a>
                    </div>
                @endif

            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('project.adminIndex') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

        </div>
    </div>

    <div class="modal fade" id="paymentImagesModal" tabindex="-1" aria-labelledby="paymentImagesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <p class="mb-0" id="totalPaidAmount" style="font-size: 1.25rem; font-weight: bold;">
                            Total Paid: <span class="text-success">₱{{ number_format($projects->total_paid, 2) }}</span>
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="paymentImagesContainer">
                    <!-- Payment images will be injected here as cards -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    </div>

    <style>
        /* Additional styles for a modern project view */
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
            padding: 20px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .btn-link {
            padding: 0;
            /* Remove padding */
            color: #17a2b8;
            /* Bootstrap's info color */
            /* Ensure the icon inherits the current text color */
        }


        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
        }

        .table td {
            font-size: 14px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        hr {
            border: 1px solid #e9ecef;
        }

        p i {
            margin-right: 5px;
        }

        p {
            font-size: 14px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                padding: 0;
            }

            .card {
                width: 100%;
                box-shadow: none;
                border: 1px solid #ddd;
                background-color: #fff;
            }

            .btn {
                display: none;
            }

            .card-header {
                background-color: #17a2b8;
                color: #fff;
            }

            .table th,
            .table td {
                color: #000;
            }

            hr {
                border: 1px solid #17a2b8;
            }
        }
    </style>

    <script>
        const paymentImagesModal = document.getElementById('paymentImagesModal');
        paymentImagesModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const images = button.getAttribute('data-images'); // Extract the images from data attribute
            const amounts = button.getAttribute('data-amounts'); // Extract the amounts from data attribute

            const imagesContainer = document.getElementById('paymentImagesContainer');
            imagesContainer.innerHTML = ''; // Clear previous images

            if (images && amounts) {
                let parsedImages = JSON.parse(images); // Parse JSON string to an array
                let parsedAmounts = JSON.parse(amounts); // Parse JSON string to an array

                // Reverse arrays to show latest images first
                parsedImages = parsedImages.reverse();
                parsedAmounts = parsedAmounts.reverse();

                if (Array.isArray(parsedImages) && parsedImages.length > 0) {
                    parsedImages.forEach((image, index) => {
                        // Create a card for each image
                        const cardElement = document.createElement('div');
                        cardElement.classList.add('card', 'm-2'); // Add Bootstrap card classes
                        cardElement.style.border = "2px solid"; // Set the border color and width

                        const imgElement = document.createElement('img');
                        imgElement.src = `{{ asset('storage') }}/${image}`; // Set image source
                        imgElement.alt = 'Payment Image';
                        imgElement.classList.add('card-img-top',
                        'img-fluid'); // Add Bootstrap classes for responsive images

                        // Append image to card
                        cardElement.appendChild(imgElement);

                        // Add payment amount below the image
                        const amountElement = document.createElement('div');
                        amountElement.classList.add('card-body');
                        amountElement.innerHTML =
                            `<h5 class="fw-bold">Amount Paid: ₱${parseFloat(parsedAmounts[index]).toLocaleString()}</h5>`;

                        cardElement.appendChild(amountElement); // Add the amount to the card
                        imagesContainer.appendChild(cardElement); // Append card to container
                    });
                } else {
                    imagesContainer.innerHTML = '<p>No images available.</p>'; // Handle no images case
                }
            } else {
                imagesContainer.innerHTML = '<p>No images available.</p>'; // Handle no images case
            }
        });
    </script>



@endsection
