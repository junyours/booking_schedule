@extends('layouts.app')

@section('content')
    <!-- Display Project Information -->
    <div class="mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Progress for Project: <span class="text-light">{{ $project->id }}</span></h4>
                <a href="{{ route('project.index') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">
                <h5 class="text-muted">Services:
                    @php
                        // Decode service IDs from JSON format
                        $serviceIds = $project->service_ids ? json_decode($project->service_ids) : [];
                        // Retrieve services based on decoded IDs
                        $services = !empty($serviceIds)
                            ? \App\Models\Service::whereIn('id', $serviceIds)->get()
                            : collect();
                    @endphp

                    @if ($services->isNotEmpty())
                        @foreach ($services as $service)
                            <span class="badge bg-secondary text-white">{{ $service->name }}</span>
                            @if (!$loop->last)
                                <span class="text-muted">, </span>
                            @endif <!-- Add a comma between services except for the last one -->
                        @endforeach
                    @else
                        <span class="text-danger">No services found</span>
                    @endif
                </h5>

                @php
                    $lastProgress = $progress->last(); // Get the last progress entry
                @endphp

                <div class="mt-3">
                    @if ($latestProgress)
                        <!-- Use the latest progress entry here -->
                        @if ($latestProgress->phase == 'phase_three' && $latestProgress->phase_progress == 100)
                            <strong class="text-success">Current Phase:</strong>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="text-success ms-2">Project Finished</span>
                        @else
                            <strong><i class="fas fa-hourglass-half text-warning"></i> Current Phase:</strong>
                            <span class="text-success ms-2">
                                @switch($latestProgress->phase)
                                    @case('phase_one')
                                        Phase one
                                    @break

                                    @case('phase_two')
                                        Phase two
                                    @break

                                    @case('phase_three')
                                        Phase three
                                    @break

                                    @default
                                        Unknown phase
                                @endswitch
                            </span><br>
                            <strong><i class="fas fa-tasks text-info"></i> Current Progress:</strong>
                            <span class="text-success ms-2">{{ $latestProgress->phase_progress }}%</span>
                        @endif
                    @else
                        <div class="text-center text-danger">
                            <i class="fas fa-exclamation-circle fa-2x"></i><br>
                            <strong>No progress recorded for this project yet.</strong>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>


    <table class="table table-hover table-bordered shadow-sm">
        <thead class="thead-light">
            <tr>
                <th><i class="fas fa-chart-line"></i> Phase</th>
                <th><i class="fas fa-chart-line"></i> Phase Progress</th>
                <th><i class="fas fa-image"></i> Image</th>
                <th><i class="fas fa-comment-dots"></i> Remarks</th>
                <th><i class="fas fa-calendar-alt"></i> Updated at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($progress as $item)
                <tr class="hover-animation">
                    <td class="font-weight-bold">{{ $item->phase }}</td>
                    <td class="font-weight-bold">{{ $item->phase_progress }}%</td>
                    <td>
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="Project Image"
                                class="img-thumbnail img-preview" data-image="{{ asset('storage/' . $item->image) }}"
                                data-remarks="{{ $item->remarks }}" style="width: 100px; cursor: pointer;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td class="text-secondary">{{ $item->remarks }}</td>
                    <td class="text-secondary">{{ $item->created_at->format('F j, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrapper">
        {{ $progress->links('pagination::bootstrap-4') }}
    </div>



    <!-- Modal for Updating Progress -->
    <div class="modal fade" id="updateProjectProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="updateProjectProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateProjectProgressModalLabel">Update Project Progress</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateProjectProgressForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">

                        <div class="form-group">
                            <div id="errorMessages" class="text-danger" style="display: none;"></div>
                            <input type="hidden" id="project_phase" name="phase" required>
                            <label>Current Phase</label>
                            <span id="currentPhaseDisplay" class="form-control-plaintext" readonly></span>
                        </div>

                        <div class="form-group">
                            <label for="project_phase_progress">Phase Progress</label>
                            <select id="project_phase_progress" class="form-control custom-select" name="phase_progress"
                                required>
                                <option value="">Select Progress</option>
                                @for ($i = 0; $i <= 100; $i += 10)
                                    <option value="{{ $i }}">{{ $i }}%</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="project_image">Upload Image</label>
                            <input type="file" class="form-control-file" id="project_image" name="image"
                                accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label for="project_remarks">Remarks</label>
                            <textarea class="form-control" id="project_remarks" name="remarks" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveProjectProgressButton" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1100px;">
            <!-- Reduced width for a more balanced modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Progress Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <!-- Image with rounded corners, shadow, and centered alignment -->
                    <img id="expandedImage" src="" alt="Expanded Project Image"
                        class="img-fluid rounded shadow-sm mb-3"
                        style="border: 4px solid #ddd; width: 100%; height: auto; max-width: 900px;">
                    <!-- Adjusted max-width -->

                    <!-- Remarks Section with top margin -->
                    <div id="remarks" class="mt-3">
                        <h6>Remarks:</h6>
                        <p id="projectRemarks" class="text-muted" style="font-size: 1rem;"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>







    @section('scripts')
        <script>
            function openUpdateModal(projectId) {
                const lastProgress = @json($progress->last());
                let phase = "phase_one";
                let currentProgress = 0;

                if (lastProgress) {
                    currentProgress = lastProgress.phase_progress;
                    phase = currentProgress == 100 ? (lastProgress.phase === "phase_one" ? "phase_two" : "phase_three") :
                        lastProgress.phase;
                }

                document.getElementById('project_id').value = projectId;
                document.getElementById('project_phase').value = phase;
                document.getElementById('currentPhaseDisplay').innerText = phase.replace(/_/g, ' ').replace(/\b\w/g, c => c
                    .toUpperCase());
                $('#updateProjectProgressModal').modal('show');

                const progressSelect = document.getElementById('project_phase_progress');
                Array.from(progressSelect.options).forEach(option => {
                    option.disabled = parseInt(option.value) <= currentProgress;
                    option.classList.toggle('disabled-option', parseInt(option.value) <= currentProgress);
                });
            }

            document.getElementById('saveProjectProgressButton').addEventListener('click', function() {
                const formData = new FormData(document.getElementById('updateProjectProgressForm'));

                fetch('{{ route('progress.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            document.getElementById('errorMessages').innerHTML = data.error ||
                                'An error occurred. Please try again.';
                            document.getElementById('errorMessages').style.display = 'block';
                        }
                    })
                    .catch(() => {
                        document.getElementById('errorMessages').innerHTML = 'An error occurred. Please try again.';
                        document.getElementById('errorMessages').style.display = 'block';
                    });
            });

            document.querySelectorAll('.img-preview').forEach(img => {
                img.addEventListener('click', function() {
                    document.getElementById('expandedImage').src = img.dataset.image;
                    document.getElementById('projectRemarks').innerText = img.dataset.remarks ||
                        'No remarks available.';
                    $('#imageModal').modal('show');
                });
            });
        </script>
    @endsection
    </div>
    @section('style')
        <style>
            body {
                background: linear-gradient(135deg, #0a60b7 25%, #1e6dbb 100%);
                /* Light gradient for a subtle modern look */
                background-attachment: fixed;
                /* Keeps the background fixed during scroll */
            }

            .table-hover tbody tr:hover {
                background-color: rgba(233, 236, 239, 0.5);
                /* Semi-transparent hover effect */
                /* Slightly transparent hover effect for better aesthetics */
            }

            .badge-info {
                background-color: #17a2b8;
                /* Bootstrap info color */
            }

            .hover-animation:hover {
                background-color: rgba(241, 241, 241, 0.7);
                /* Slightly transparent hover effect for table rows */
                transform: scale(1.02);
                transition: background-color 0.3s, transform 0.3s;
            }

            .img-preview {
                border-radius: 5px;
                /* Rounded corners for images */
                transition: transform 0.3s;
                /* Smooth transform effect */
            }

            .img-preview:hover {
                transform: scale(1.05);
                /* Slight zoom on hover */
            }

            .disabled-option {
                color: #ccc;
                /* Grey out disabled options */
            }
        </style>
    @endsection
@endsection
