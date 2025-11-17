@extends('layouts.app')

@section('content')
    <div class="pricing-factors mb-4">
        <h5>Payment Overview</h5>
        <p>Below is a list of all your payments, including their details and current status.</p>
    </div>
    <div class="container-fluid mt-4">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-light">
            <div class="card-header bg-info text-white d-flex justify-content-between">
                <h4 class="mb-0">Payment Records</h4>
                <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm ml-auto">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">

                <!-- Filter and Sort Form -->
                <form action="{{ route('payments.index') }}" method="GET" class="mb-4">
                    <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->

                        <!-- Start Date Filter -->
                        <div class="me-2"> <!-- Added margin to the right -->
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                    value="{{ request('start_date') }}">
                            </div>
                        </div>

                        <!-- End Date Filter -->
                        <div class="me-2"> <!-- Added margin to the right -->
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-sm"> <!-- Added 'btn-sm' for smaller size -->
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </form>

                @if ($payments->isEmpty())
                    <p class="text-muted">You do not have any Payment Records</p>
                @else
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i></th>
                                <th><i class="fas fa-project-diagram"></i> Project ID</th>
                                <th><i class="fas fa-wallet"></i> Payment Method</th>
                                <th><i class="fas fa-credit-card"></i> Payment Type</th>
                                <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                <th><i class="fas fa-image"></i> Image</th>
                                <th><i class="fas fa-calendar-alt"></i> Payment Date</th>
                                <th><i class="fas fa-cog"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->project_id }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>{{ ucfirst($payment->payment_type) }}</td>
                                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if ($payment->payment_image)
                                            <a href="#" data-toggle="modal" data-target="#imageModal"
                                                data-image="{{ asset('storage/' . $payment->payment_image) }}"
                                                data-amount="₱{{ number_format($payment->amount, 2) }}">
                                                <img src="{{ asset('storage/' . $payment->payment_image) }}"
                                                    alt="Payment Image"
                                                    style="width: 100px; height: auto; border: 1px solid #ccc;">
                                            </a>
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at->format('F j, Y') }}</td>

                                    <td>
                                        <div
                                            style="display: flex; justify-content: center; align-items: center; gap: 10px; padding: 8px 0;">
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Project">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <div class="pagination-wrapper">
                    {{ $payments->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Payment Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center" style="min-height: 300px;">
                    <img id="modalImage" src="" alt="Payment Image" class="img-fluid"
                        style="transition: transform 0.3s ease; max-width: 100%; max-height: 80vh; border: 3px solid black;">
                </div>
            </div>
        </div>
    </div>
</div>



    <style>
        /* Container styling to remove padding for full-width table */
        .container-fluid {
            padding: 0;
        }

        /* Table styling */
        .table {
            font-size: 15px;
            /* Increase font size */
        }

        th,
        td {
            padding: 15px;
            /* Increase cell padding */
        }

        th {
            background-color: #d3d3d3;
            /* Grey background for table headers */
            color: #333;
            /* Optional: change text color for better visibility */
        }
    </style>

@section('scripts')
    <script>
        $(document).ready(function() {
            // Event listener for image clicks
            $('a[data-toggle="modal"]').on('click', function() {
                var imageUrl = $(this).data('image'); // Get the image URL from data attribute
                var amount = $(this).data('amount'); // Get the amount from data attribute
                $('#modalImage').attr('src', imageUrl); // Set the image source in the modal
                $('#amountValue').text(amount); // Set the amount text in the span
            });
        });
    </script>
@endsection
@endsection
