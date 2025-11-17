@extends('layouts.app')

@section('title', 'Quotations')
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
@section('content')
    <div class="pricing-factors mb-4">
        <h5>Quotation Overview</h5>
        <p>Below is a list of all your Quotations, including their details and current status.</p>
    </div>
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">My Quotations</h4>
            <div>
                <a href="{{ route('quotation.create') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-plus"></i> Add Quotation
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($quotations->isEmpty())
                <p class="text-muted">You do not have any quotations at this time. Please contact us to request a quotation.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th><i class="fas fa-numeric icon-faded-gray"></i> #</th>
                                <th><i class="fas fa-map-marker-alt icon-faded-gray"></i> Address</th>
                                <th><i class="fas fa-globe icon-faded-gray"></i> Region</th>
                                <th><i class="fas fa-city icon-faded-gray"></i> City</th>
                                <th><i class="fas fa-arrows-alt-h icon-faded-gray"></i> Lot Area</th>
                                <th><i class="fas fa-leaf icon-faded-gray"></i> Service Name</th> <!-- Landscaping icon -->
                                <th><i class="fas fa-money-bill-wave icon-faded-gray"></i> Total Amount</th>
                                <th><i class="fas fa-calendar-alt icon-faded-gray"></i> Working Days</th>
                                <th><i class="fas fa-cogs icon-faded-gray"></i> Actions</th>
                            </tr>


                        </thead>
                        <tbody>
                            @foreach ($quotations as $quotation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $quotation->address }}</td>
                                    <td>{{ $quotation->region }}</td>
                                    <td>{{ $quotation->city }}</td>
                                    <td>{{ number_format($quotation->lot_area, 0) }} sqm</td>
                                    <td>{{ $quotation->service->name }}</td>
                                    <td>₱{{ number_format($quotation->amount) }}</td>
                                    <td>{{ $quotation->working_days }} Days</td>
                                    <td>
                                        <div
                                            style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                            <a href="{{ route('quotation.details', $quotation->id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Quotation">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="pagination-wrapper">
                {{ $quotations->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 8px;
            border: none;
        }

        .card-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
            border: none;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }

        .btn {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-info {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .pagination-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-wrapper .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-wrapper .page-item {
            margin: 0 2px;
        }

        .pagination-wrapper .page-link {
            padding: 10px 15px;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection
