@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-light">
            <div class="card-header text-white bg-info text-center d-flex flex-column align-items-center">
                <img src="{{ asset('arfil_logo.png') }}" alt="Company Logo" class="logo" style="width: 120px">
                <h2 class="mb-1">Arfils Landscaping Services</h2>
                <p><i class="fas fa-map-marker-alt"></i> Zone 10, Carmen Cagayan de Oro City</p>
                <p>
                    <i class="fas fa-phone"></i> Contact: 09776912110<br>
                    <i class="fab fa-facebook"></i> Facebook: Arfils Landscaping and Swimming Pool Services<br>
                    <i class="fas fa-envelope"></i> Email: arifillandscaping@gmail.com
                </p>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h4 class="mb-0">Booking Details</h4>
                    <h3 class="mb-0" style="font-size: 16px;">
                        Booked At:
                        {{ \Carbon\Carbon::parse($booking->created_at)->format('F j, Y ') }}
                    </h3>
                </div>

                <table class="table">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $booking->name }}</td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td>{{ $booking->contact }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $booking->email }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $booking->address }}</td>
                        </tr>
                        <tr>
                            <th>Province</th>
                            <td>{{ $booking->province }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $booking->city }}</td>
                        </tr>
                        <tr>
                            <th>Site Visit Date</th>
                            <td>
                                @if ($booking->site_visit_date)
                                    {{ (new DateTime($booking->site_visit_date))->format('F j, Y') }}
                                @else
                                    No site visit scheduled.
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Booking Status</th>
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
                        </tr>
                    </tbody>
                </table>

                <hr class="my-4">

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('booking.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @if ($booking->booking_status === 'confirmed')
                        <button class="btn btn-info" onclick="printDocument()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Additional styles for a modern booking view look */
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

        /* Style for Booking Terms */
        .payment-terms {
            margin-top: 20px;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
        }

        .payment-terms h4 {
            margin-bottom: 10px;
        }

        @media print {

            /* Ensure colors are retained when printing */
            body {
                -webkit-print-color-adjust: exact;
                /* Chrome, Safari */
                print-color-adjust: exact;
                /* Non-Webkit */
            }

            .btn {
                display: none;
                /* Hide buttons when printing */
            }

            /* Optional: hide unnecessary elements or modify styles */
            .card-header {
                background-color: #17a2b8;
                /* Ensure header color is retained */
            }

            .project-terms {
                background-color: #f1f1f1;
                /* Ensure background color is retained */
            }

            /* Align print buttons in the desired positions */
            .d-flex {
                justify-content: space-between !important;
            }
        }
    </style>

    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        function printDocument() {
            window.print();
        }
    </script>
@endsection
