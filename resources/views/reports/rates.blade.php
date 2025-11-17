@extends('layouts.apps')

@section('content')
    <div class="card shadow-sm rounded-lg border-1">
        <div class="card shadow-sm border-light">
            <div class="card-header stylish-header text-black d-flex justify-content-between align-items-center">
                <h1>Payment Report</h1>
                <!-- Print Button -->
                <button onclick="window.print()" class="btn btn-info print-hide">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">
                <!-- Filter by Date Form -->
                <form action="{{ route('reports.rates') }}" method="GET" class="mb-4 print-hide">
                    <div class="d-flex align-items-center">
                        <!-- Start Date Filter -->
                        <div class="me-2">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                        </div>

                        <!-- End Date Filter -->
                        <div class="me-2">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </form>

                @php
                    $oldestDate = \App\Models\Payment::oldest('created_at')->value('created_at');
                    $latestDate = \App\Models\Payment::latest('created_at')->value('created_at');

                    $startDate = request('start_date')
                        ? \Carbon\Carbon::parse(request('start_date'))->format('F j, Y')
                        : \Carbon\Carbon::parse($oldestDate)->format('F j, Y');
                    $endDate = request('end_date')
                        ? \Carbon\Carbon::parse(request('end_date'))->format('F j, Y')
                        : \Carbon\Carbon::parse($latestDate)->format('F j, Y');
                @endphp

                <!-- Display Revenue Date Range -->
                <div class="revenue-date-range">
                    <h5 class="text-muted">
                        Payments: from {{ $startDate }} to {{ $endDate }}
                    </h5>
                </div>
            </div>

            <table class="table table-bordered table-striped"  style="width: 100%;">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Payment Method</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th class="print-hide">Action</th> <!-- Hide Action column on print -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->project_id }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>{{ ucfirst($payment->payment_type) }}</td>
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->created_at->format('F j, Y') }}</td>
                            <td class="print-hide">
                                <div
                                    style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm"
                                        style="background-color: transparent; border: none; color: #17a2b8; outline: none; "
                                        data-toggle="tooltip" title="View Payment">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Wrapper (Hidden on Print) -->
            @if ($payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination-wrapper print-hide">
                    {{ $payments->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            @endif

            <!-- Display Total Revenue -->
            <div class="mt-4">
                <h5 class="total-revenue">
                    <i class="fas fa-calculator"></i> Total Payment: <i
                        style="color: #28a745;">₱{{ number_format($totalRevenue, 2) }}</i>
                </h5>
            </div>

        </div>


        <style>
            /* Hide elements with class print-hide when printing */
            @media print {
                
                .print-hide {
                    display: none !important;
                }

                /* Hide the Action column header and cells when printing */
                th.print-hide,
                td.print-hide {
                    display: none !important;
                }

                /* Display the Revenue Date Range for Printing */
                .revenue-date-range {
                    display: block !important;
                }

                /* Adjust table size and fonts for better printing */
                .table {
                    font-size: 12px;
                    width: 100% !important;
                }

                th,
                td {
                    padding: 10px;
                }
            }

            .container-fluid {
                padding: 0;
            }

            .table {
                font-size: 15px;
            }

            th,
            td {
                padding: 15px;
            }

            th {
                background-color: #d3d3d3;
            }

            .total-revenue {
                font-weight: bold;
            }

            @media (max-width: 768px) {
                .table-responsive {
                    overflow-x: auto;
                }
            }
        </style>
    @endsection

    @push('scripts')
        <script>
            document.querySelectorAll('.payment-image').forEach(item => {
                item.addEventListener('click', event => {
                    const imageUrl = event.currentTarget.getAttribute('data-image-url');
                    document.getElementById('modalImage').src = imageUrl;
                    $('#imageModal').modal('show');
                });
            });
        </script>
    @endpush
