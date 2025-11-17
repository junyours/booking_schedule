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
                    <h4 class="mb-0">Payment Details</h4>
                    <h3 class="mb-0" style="font-size: 16px;">Paid At: {{ $payment->created_at->format('F j, Y') }}</h3>
                </div>

                <table class="table">
                    <tbody>
                        <tr>
                            <th>Project ID</th>
                            <td>{{ $payment->project_id }}</td>
                        </tr>
                        <tr>
                            <th>Payment Type</th>
                            <td>{{ ucfirst($payment->payment_type) }}</td>
                        </tr>
                       
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Image</th>
                            <td>
                                @if ($payment->payment_image)
                                    <img src="{{ asset('storage/' . $payment->payment_image) }}" alt="Payment Image"
                                        class="payment-image">
                                @else
                                    No Image Uploaded
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $payment->remarks }}</td>
                        </tr>
                    </tbody>
                </table>

                <hr class="my-4">

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                        <button class="btn btn-info" onclick="printDocument()">
                            <i class="fas fa-print"></i> Print
                        </button>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Additional styles for a modern receipt look */
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
            color: #495057;

        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .table td {
            font-size: 14px;
        }

        .payment-image {
            width: 100px;
            height: auto;
            border: 1px solid #ccc;
            display: block;
            margin: 0 auto;
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

        /* Style for Payment Terms */
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
