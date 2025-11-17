@extends('layouts.apps')

@section('content')
    <div class="card shadow-sm rounded-lg border-1">
        <div class="card-header stylish-header text-black">
            <h1> Tasklog</h1>
        </div>

        <!-- Filter and Sort Form -->
        <form action="{{ route('admin.tasklog.index') }}" method="GET" class="mb-4">
            <div class="d-flex align-items-center">

                <!-- Type Filter -->
                <div class="me-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                        <select name="type" class="form-select form-select-sm" style="max-width: 120px;">
                            <option value="">All Types</option>
                            <option value="booking" {{ request('type') == 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="project" {{ request('type') == 'project' ? 'selected' : '' }}>Project</option>
                            <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Payment</option>
                            <option value="progress" {{ request('type') == 'progress' ? 'selected' : '' }}>Progress</option>
                            <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service</option>
                        </select>
                    </div>
                </div>

                <!-- Start Date Filter -->
                <div class="me-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>
                </div>

                <!-- End Date Filter -->
                <div class="me-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>

        @if ($taskLogs->isEmpty())
            <p class="text-muted">You do not have any task logs at this time.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Type</th>
                            <th>Action</th>
                            <th>Action Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taskLogs as $log)
                            <tr>
                                <td>{{ $log->type }}</td>
                                <td>
                                    @if (str_contains(strtolower($log->action), 'created'))
                                        <i class="fas fa-plus-circle text-success"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'updated'))
                                        <i class="fas fa-edit text-warning"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'deleted'))
                                        <i class="fas fa-trash-alt text-danger"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'payment'))
                                        <i class="fas fa-credit-card text-primary"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'active'))
                                        <i class="fas fa-check-circle text-success"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'hold'))
                                        <i class="fas fa-pause-circle text-warning"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'decline'))
                                        <i class="fas fa-times-circle text-danger"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'confirm'))
                                        <i class="fas fa-check text-info"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'archive'))
                                        <i class="fas fa-archive text-secondary"></i> {{ $log->action }}
                                    @elseif(str_contains(strtolower($log->action), 'available'))
                                        <i class="fas fa-check-circle text-success"></i> {{ $log->action }}
                                        <!-- Or choose a different icon -->
                                    @else
                                        <i class="fas fa-info-circle text-secondary"></i> {{ $log->action }}
                                    @endif
                                </td>

                                <td>{{ \Carbon\Carbon::parse($log->action_date)->format('F j, Y') }}</td>
                                <td>
                                    <div
                                        style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px;">
                                        @if (trim($log->type) === 'Project')
                                            <a href="{{ route('project.adminShow', $log->type_id) }}"
                                                class="btn btn-sm text-info" data-toggle="tooltip" title="View Project">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Booking')
                                            <a href="{{ route('booking.adminShow', $log->type_id) }}"
                                                class="btn btn-sm text-info" data-toggle="tooltip" title="View Booking">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Payment')
                                            <a href="{{ route('admin.payments.show', $log->type_id) }}"
                                                class="btn btn-sm text-info" data-toggle="tooltip" title="View Payment">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Progress')
                                            @php
                                                $redirectRoute = route('progress.index', [
                                                    'projectId' => $log->type_id,
                                                ]);
                                            @endphp
                                            <a href="{{ $redirectRoute }}" class="btn btn-sm text-info"
                                                data-toggle="tooltip" title="View Progress">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (str_contains(trim($log->type), 'Landscaping'))
                                            <a href="{{ route('landscape', $log->type_id) }}" class="btn btn-sm text-info"
                                                data-toggle="tooltip" title="View Landscaping Service">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (str_contains(trim($log->type), 'Swimmingpool'))
                                            <a href="{{ route('swimmingpool', $log->type_id) }}"
                                                class="btn btn-sm text-info" data-toggle="tooltip"
                                                title="View Swimming Pool Service">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (str_contains(trim($log->type), 'Renovation'))
                                            <a href="{{ route('renovation', $log->type_id) }}" class="btn btn-sm text-info"
                                                data-toggle="tooltip" title="View Renovation Service">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (str_contains(trim($log->type), 'Package'))
                                            <a href="{{ route('package', $log->type_id) }}" class="btn btn-sm text-info"
                                                data-toggle="tooltip" title="View Package Service">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (str_contains(trim($log->type), 'Rate'))
                                            <a href="{{ route('rates.index', $log->type_id) }}" class="btn btn-sm text-info"
                                                data-toggle="tooltip" title="View Rate">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (str_contains(trim($log->type), 'Archive'))
                                            <a href="{{ route('archive.index', $log->type_id) }}"
                                                class="btn btn-sm text-info" data-toggle="tooltip"
                                                title="View Archive Service">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    {{ $taskLogs->withQueryString()->links('pagination::bootstrap-4') }}
                    <!-- Retain query string in pagination links -->
                </div>
            </div>
        @endif
    </div>
@endsection
