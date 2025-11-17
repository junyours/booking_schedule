@extends('layouts.apps')

@section('title', 'Archived Services')

@section('content')


    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                    <h4>Archived Services</h4>
                <div class="btn-group ml-2">

                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Filter By
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{ request('filter') === 'all' ? 'active' : '' }}"
                            href="{{ route('archive.index', ['filter' => 'all']) }}">All Services</a>
                        <a class="dropdown-item {{ request('filter') === 'landscaping' ? 'active' : '' }}"
                            href="{{ route('archive.index', ['filter' => 'landscaping']) }}">Landscaping</a>
                        <a class="dropdown-item {{ request('filter') === 'swimmingpool' ? 'active' : '' }}"
                            href="{{ route('archive.index', ['filter' => 'swimmingpool']) }}">Swimming Pool</a>
                        <a class="dropdown-item {{ request('filter') === 'renovation' ? 'active' : '' }}"
                            href="{{ route('archive.index', ['filter' => 'renovation']) }}">Renovation</a>
                        <a class="dropdown-item {{ request('filter') === 'package' ? 'active' : '' }}"
                            href="{{ route('archive.index', ['filter' => 'package']) }}">Package</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Design</th>
                            <th>Name</th>
                            <th>Complexity</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td style="text-align: center;">
                                    @if ($service->design)
                                        <img src="{{ asset('storage/' . $service->design) }}" alt="{{ $service->name }}"
                                            style="width: 100px; height: auto; border: 1px solid #ccc; display: block; margin: 0 auto;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $service->name }}</td>
                                <td>{{ ucfirst($service->complexity) }}</td>
                                <td>{{ ucfirst($service->category) }}</td>
                                <td>{{ $service->description }}</td>
                                <td>
                                    <div
                                        style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                        <button class="btn btn-sm"
                                            style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                            data-toggle="modal" title="View Service" data-target="#serviceModal"
                                            data-id="{{ $service->id }}" data-name="{{ $service->name }}"
                                            data-description="{{ $service->description }}"
                                            data-design="{{ asset('storage/' . $service->design) }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm"
                                            style="background-color: transparent; border: none; color: #ffc107; outline: none;"
                                            data-toggle="modal" data-target="#confirmRestoreModal"
                                            data-id="{{ $service->id }}" data-name="{{ $service->name }}">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button class="btn btn-sm"
                                            style="background-color: transparent; border: none; color: #dc3545; outline: none;"
                                            data-toggle="modal" data-target="#confirmDeleteModal"
                                            data-id="{{ $service->id }}" data-name="{{ $service->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No archived services found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    {{ $services->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="float: right;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <img id="serviceImage" src="" alt="Service Image"
                                    style="width: 100%; height: auto; border: 1px solid #ccc;">
                            </div>
                        </div>
                        <div class="vertical-divider"></div>
                        <div class="col-md-5">
                            <div class="text-left">
                                <h3 id="serviceName" class="mt-3"
                                    style="font-family: Arial, Helvetica, sans-serif; font-weight: bold;"></h3>
                                <p id="serviceDescription"
                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem; line-height: 1.5;">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Restore Modal -->
    <div class="modal fade" id="confirmRestoreModal" tabindex="-1" aria-labelledby="confirmRestoreModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRestoreModalLabel">Confirm Restoration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to restore the service <strong id="serviceToRestoreName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form id="restoreForm" method="POST" style="display: inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning">Restore</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the service <strong id="serviceToDeleteName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .vertical-divider {
            width: 1px;
            background-color: #ccc;
            margin-left: 15px;
            margin-right: 15px;
            height: 100%;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
    </style>
@endsection

@section('scripts')
    <script>
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

        $('#confirmRestoreModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');

            var modal = $(this);
            modal.find('#serviceToRestoreName').text(name);
            var form = modal.find('#restoreForm');
            form.attr('action', '/archive/' + id + '/restore');
        });

        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');

            var modal = $(this);
            modal.find('#serviceToDeleteName').text(name);
            var form = modal.find('#deleteForm');
            form.attr('action', '/archive/' + id);
        });
    </script>
@endsection
