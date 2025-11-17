@extends('layouts.apps')

@section('title', 'Landscape Services')

@section('content')
<h4>Package Services</h4>

    <div class="card">
        <div class="card-header stylish-header">
            <div class="card-tools">
                <a href="{{ route('package-services.create') }}" class="btn btn-info"><i class="fas fa-plus me-1"></i>Add New</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>ID</th>
                            <th>Design</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Complexity</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $package)
                                <tr>
                                    <td>{{ $package->id }}</td>
                                    <td style="text-align: center;">
                                        @if ($package->design)
                                            <img src="{{ asset('storage/' . $package->design) }}" alt="{{ $package->name }}"
                                                style="width: 100px; height: auto; border: 1px solid #ccc; display: block; margin: 0 auto;">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{ $package->name }}</td>
                                    <td>{{ $package->category }}</td>
                                    <td>{{ $package->complexity }}</td>
                                    <td>{{ $package->description }}</td>
                                    <td>
                                        <div style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                            <button class="btn btn-sm" style="background-color: transparent; border: none; color: #17a2b8; outline: none;" data-toggle="modal" title="View Service" data-target="#serviceModal" data-id="{{ $package->id }}" data-name="{{ $package->name }}" data-description="{{ $package->description }}" data-design="{{ asset('storage/' . $package->design) }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('package-services.edit', $package->id) }}" class="btn btn-sm" style="background-color: transparent; border: none; color: #007bff; outline: none;" data-toggle="tooltip" title="Edit Service">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm" style="background-color: transparent; border: none; color: #ffc107; outline: none;" data-toggle="modal" data-target="#archiveConfirmModal" data-toggle="tooltip" title="Archive Service" data-id="{{ $package->id }}" data-name="{{ $package->name }}">
                                                <i class="fas fa-archive" style="color: #ffc107;"></i>
                                            </button>
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
            </div>
            <!-- Pagination Controls -->
            <div class="pagination-wrapper">
                {{ $packages->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="card">
                        <div class="card-img-top">
                            <img id="serviceImage" src="" alt="Service Image" class="img-fluid">
                        </div>
                        <div class="card-body">
                            <h4 id="serviceName" class="mb-3 font-weight-bold"></h4>
                            <p id="serviceDescription"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div class="modal fade" id="archiveConfirmModal" tabindex="-1" aria-labelledby="archiveConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveConfirmModalLabel">Confirm Archive</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="archiveConfirmMessage">Are you sure you want to archive <strong
                            id="archiveServiceName"></strong>?</p>
                    <form id="archiveForm" action="" method="POST">
                        @csrf
                        @method('put')
                        <button type="button" class="btn btn-primary" id="confirmArchiveYes">Yes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header {
            background: linear-gradient(135deg, #4CAF50, #81C784);
            /* Green Gradient */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .card-tools .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #007bff;
            color: #fff;
            text-align: center;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .table td,
        .table th {
            padding: 15px;
            vertical-align: middle;
            text-align: center;
        }

        .table img {
            border-radius: 5px;
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
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .custom-alert {
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid transparent;
        }

        .custom-alert.alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .custom-alert.alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .modal-dialog.modal-md {
            max-width: 800px;
        }

        .modal-dialog.modal-sm {
            max-width: 300px;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .modal-body {
            padding: 20px;
        }

        .btn-info,
        .btn-primary,
        .btn-warning {
            padding: 5px 10px;
            /* Adjust button padding */
        }
    </style>
@endsection

@section('scripts')
    <script>
        $('#archiveConfirmModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var serviceId = button.data('id');
            var serviceName = button.data('name');

            var modal = $(this);
            modal.find('#archiveServiceName').text(serviceName);
            modal.find('#archiveForm').attr('action', '{{ route('package-services.archive', ':id') }}'.replace(
                ':id', serviceId));
        });

        $('#confirmArchiveYes').click(function() {
            $('#archiveForm').submit();
        });

        $('#serviceModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var description = button.data('description');
            var design = button.data('design');

            var modal = $(this);
            modal.find('#serviceImage').attr('src', design);
            modal.find('#serviceName').text(name);
            modal.find('#serviceDescription').text(description);
        });
    </script>
@endsection
