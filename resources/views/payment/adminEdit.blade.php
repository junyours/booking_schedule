@extends('layouts.apps')

@section('content')
    <div class="container mt-4" style="padding: 20px; border-radius: 10px;">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card for Payment Details -->
        <div class="card shadow-sm border-light">
            <div class="card-header bg-info text-white">
                <h3 class="mb-0"><i class="fas fa-money-check-alt"></i> Payment for Project: {{ $project->id }}</h3>
            </div>
            <div class="card-body d-flex" style="align-items: stretch;">
         
                <!-- Payment Form Section -->
                <form id="initialPaymentForm" method="POST" action="{{ route('admin.payments.update', $payment->id) }}" enctype="multipart/form-data" style="flex: 1; padding: 20px;">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="" disabled>Select Payment Method</option>
                            <option value="cash" {{ old('payment_method', $payment->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="gcash" {{ old('payment_method', $payment->payment_method) === 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Payment Amount Input Section -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Payment Amount <span style="color: red;">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control" required
                            min="{{ $project->total_paid == 0 ? $project->total_cost * 0.50 : 0 }}"
                            max="{{ $project->total_cost - $project->total_paid }}" step="0.01"
                            value="{{ old('amount', $project->total_paid == 0 ? $project->total_cost * 0.50 : 0) }}">
                        <div class="form-text text-primary">
                            The payment amount must be between ₱{{ number_format($project->total_cost * 0.50, 2) }} and ₱{{ number_format($project->total_cost - $project->total_paid, 2) }}.
                        </div>
                    </div>

                    @if($payment->payment_image)
                        <div class="mb-3">
                            <label class="form-label">Uploaded Payment Image</label>
                            <div>
                                <a href="#" class="payment-image" data-image-url="{{ asset('storage/' . $payment->payment_image) }}">
                                    <img src="{{ asset('storage/' . $payment->payment_image) }}" alt="Payment Image" style="width: 100px; height: auto; border: 1px solid #ccc; display: block; margin: 0 auto;">
                                </a>
                            </div>
                        </div>
                    @endif

                    <hr style="border-top: 1px solid #343a40;" />

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary hover-effect">
                            <i class="fas fa-arrow-left"></i> Back to Projects
                        </a>
                        <button type="button" class="btn btn-info hover-effect" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-paper-plane"></i> Approve Payment
                        </button>
                        <button type="button" class="btn btn-danger hover-effect" data-bs-toggle="modal" data-bs-target="#declineModal">
                            <i class="fas fa-times"></i> Decline Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Enlarged Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Payment Image</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Payment Image" style="width: 100%; height: auto; transition: transform 0.3s ease;">
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Confirm Approve Payment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this payment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="approvePaymentForm" method="POST" action="{{ route('admin.payments.update', $payment->id) }}">
                        @csrf
                        <input type="hidden" name="payment_method" id="hiddenPaymentMethod" value="">
                        <input type="hidden" name="amount" id="hiddenAmount" value="">
                        <button type="submit" class="btn btn-info">Approve Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Confirmation Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Confirm Decline Payment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to decline this payment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="declinePaymentForm" method="POST" action="{{ route('admin.payments.decline', $payment->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Decline Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentImageLinks = document.querySelectorAll('.payment-image');
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            const modalImage = document.getElementById('modalImage');

            paymentImageLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default anchor click behavior
                    const imageUrl = this.getAttribute('data-image-url');
                    modalImage.src = imageUrl; // Set the modal image source
                    modal.show(); // Show the modal
                });
            });

            // Optional: Add smooth transition effects on modal image load
            modalImage.addEventListener('load', function() {
                this.style.transform = 'scale(2.5)'; // Reset scale when the image loads
            });

            // Set hidden fields in approval modal before submitting
            const approveButton = document.querySelector('.btn-info.hover-effect');
            approveButton.addEventListener('click', function() {
                const paymentMethod = document.querySelector('select[name="payment_method"]').value;
                const amount = document.querySelector('input[name="amount"]').value;

                document.getElementById('hiddenPaymentMethod').value = paymentMethod;
                document.getElementById('hiddenAmount').value = amount;
            });
        });
    </script>
@endsection
