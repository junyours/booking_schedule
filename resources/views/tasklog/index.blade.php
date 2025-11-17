@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@section('title', 'Task Log')
@section('content')
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">Tasklog</h4>
            <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <!-- Filter and Sort Form -->
            <form action="{{ route('tasklog.index') }}" method="GET" class="mb-4">
                <div class="d-flex align-items-center">

                    <!-- Type Filter -->
                    <div class="me-2">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-filter"></i></span>
                            <select name="type" class="form-select form-select-sm" style="max-width: 120px;">
                                <option value="">All Types</option>
                                <option value="Booking" {{ request('type') == 'Booking' ? 'selected' : '' }}>Booking
                                </option>
                                <option value="Quotation" {{ request('type') == 'Quotation' ? 'selected' : '' }}>Quotation
                                </option>
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
                                <th><i class="fas fa-tools icon-faded-gray"></i> Type</th>
                                <th><i class="fas fa-flag-checkered icon-faded-gray"></i> Action</th>
                                <th><i class="fas fa-calendar-alt icon-faded-gray"></i> Action Date</th>
                                <th><i class="fas fa-cogs icon-faded-gray"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taskLogs as $log)
                                <tr>
                                    <td>{{ $log->type }}</td>
                                    <td>
                                        @php
                                            $actionText = strtolower($log->action);
                                        @endphp

                                        @if (Illuminate\Support\Str::contains($actionText, 'created'))
                                            <i class="fas fa-plus-circle text-success"></i> {{ $log->action }}
                                        @elseif (Illuminate\Support\Str::contains($actionText, 'updated'))
                                            <i class="fas fa-edit text-warning"></i> {{ $log->action }}
                                        @elseif (Illuminate\Support\Str::contains($actionText, 'deleted'))
                                            <i class="fas fa-trash-alt text-danger"></i> {{ $log->action }}
                                        @elseif (Illuminate\Support\Str::contains($actionText, 'payment'))
                                            <i class="fas fa-credit-card text-primary"></i> {{ $log->action }}
                                        @elseif (Illuminate\Support\Str::contains($actionText, 'cancel'))
                                            <i class="fas fa-ban text-danger"></i> {{ $log->action }}
                                        @else
                                            <i class="fas fa-info-circle text-secondary"></i> {{ $log->action }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($log->action_date)->format('F j, Y') }}</td>
                                    <td>
                                        <div
                                            style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                            @if (trim($log->type) === 'Quotation')
                                                <a href="{{ route('quotation.details', $log->type_id) }}"
                                                    class="btn btn-sm"
                                                    style="background-color: transparent; border: none; color: #17a2b8;"
                                                    data-toggle="tooltip" title="View Project">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif (trim($log->type) === 'Booking')
                                                <a href="{{ route('booking.view', $log->type_id) }}" class="btn btn-sm"
                                                    style="background-color: transparent; border: none; color: #17a2b8;"
                                                    data-toggle="tooltip" title="View Booking">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                {{-- Optional: Handle other types or do nothing --}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="pagination-wrapper">
                {{ $taskLogs->appends(request()->query())->links('pagination::bootstrap-4') }}
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
