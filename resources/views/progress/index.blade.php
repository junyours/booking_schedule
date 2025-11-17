@extends('layouts.apps')

@section('content')
    <div class="container">
        <div class="mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Progress for Project: <span class="text-white">{{ $project->id }}</span></h4>

                    <div class="d-flex align-items-center ms-auto">
                        @if (
                            (!$latestProgress || !($latestProgress->phase === 'phase_three' && $latestProgress->phase_progress === '100')) &&
                                $project->project_status !== 'hold')
                            <button class="btn btn-secondary btn-md position-relative me-2"
                                onclick="openUpdateModal('{{ $project->id }}')" title="Update the project's progress">
                                <i class="fas fa-plus-circle"></i> Update Progress
                                <span class="spinner-border spinner-border-sm position-absolute" id="loadingSpinner"
                                    style="display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        @endif

                        <!-- Back button -->
                        <a class="btn btn-secondary btn-md" href="{{ route('project.adminIndex') }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Go back to the project overview">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="text-muted">
                        Services:
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

                    <div class="mt-3">
                        @if ($latestProgress)
                            <!-- Display the latest progress entry -->
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

                <!-- Table of Progress Entries -->
                <table class="table table-striped">
                    <thead class="thead">
                        <tr>
                            <th><i class="fas fa-chart-line"></i> Phase</th>
                            <th><i class="fas fa-chart-line"></i> Phase Progress</th>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-comment-dots"></i> Remarks</th>
                            <th><i class="fas fa-calendar-alt"></i> Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($progress as $item)
                            <tr>
                                <td class="hover-animation">{{ $item->phase }}</td>
                                <td class="hover-animation">{{ $item->phase_progress }}%</td>
                                <td>
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Project Image"
                                            class="img-thumbnail img-preview"
                                            data-image="{{ asset('storage/' . $item->image) }}"
                                            style="width: 100px; cursor: pointer;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="hover-animation">{{ $item->remarks }}</td>
                                <td class="hover-animation">{{ $item->created_at->format('F j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    {{ $progress->links('pagination::bootstrap-4') }}
                </div>

                <!-- Modal for Updating Progress -->
                <div class="modal fade" id="updateProjectProgressModal" tabindex="-1"
                    aria-labelledby="updateProjectProgressModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title" id="updateProjectProgressModalLabel">Update Project Progress</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="updateProjectProgressForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">

                                    <div class="form-group d-flex align-items-center">
                                        <div id="errorMessages" class="text-danger" style="display: none;"></div>

                                        <input type="hidden" id="project_phase" name="phase" required>

                                        <label class="mb-0 me-5">Current Phase:</label>
                                        <span id="currentPhaseDisplay" class="form-control-plaintext mb-0" readonly></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="project_phase_progress">Phase Progress</label>
                                        <select id="project_phase_progress" class="form-control custom-select"
                                            name="phase_progress" required>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" id="saveProjectProgressButton" class="btn btn-primary">Update
                                    Progress</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Modal -->
                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 1300px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Progress Image</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body d-flex justify-content-center pt-0 pb-3"> <!-- Added padding-bottom -->
                                <img id="expandedImage" src="" alt="Expanded Project Image" class="img-fluid"
                                    style="width: 1280px; height: 720px;">
                            </div>
                        </div>
                    </div>
                </div>








            </div>

            @section('scripts')
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

                <script>
                    function openUpdateModal(projectId) {
                        // Fetch the latest progress from Blade template (passed as JSON)
                        const latestProgress = @json($latestProgress);

                        let phase = "phase_one"; // Default to phase_one
                        let currentProgress = 0;

                        // If there's a latest progress record, get its phase and phase_progress
                        if (latestProgress) {
                            phase = latestProgress.phase;
                            currentProgress = latestProgress.phase_progress;
                        }

                        // Debugging: Log the current phase and progress
                        console.log("Current Phase:", phase);
                        console.log("Current Progress:", currentProgress);

                        // Ensure currentProgress is a number (in case it's being passed as a string)
                        currentProgress = Number(currentProgress);

                        // Check for phase progression based on current phase and progress
                        if (phase === "phase_one" && currentProgress === 100) {
                            console.log("Transitioning to phase_two"); // Log when transitioning
                            phase = "phase_two"; // Move to phase_two
                            currentProgress = 0; // Reset progress for phase_two
                        } else if (phase === "phase_two" && currentProgress === 100) {
                            console.log("Transitioning to phase_three"); // Log when transitioning
                            phase = "phase_three"; // Move to phase_three
                            currentProgress = 0; // Reset progress for phase_three
                        } else if (phase === "phase_three" && currentProgress === 100) {
                            console.log("Transitioning to finished"); // Log when transitioning
                            phase = "finished"; // Final phase reached
                            currentProgress = 100; // Set progress to 100 (no more updates allowed)
                            document.getElementById('saveProjectProgressButton').disabled = true; // Disable button
                        } else {
                            document.getElementById('saveProjectProgressButton').disabled = false; // Enable button for other cases
                        }

                        // Debugging: Log the updated phase and progress
                        console.log("Updated Phase:", phase);
                        console.log("Updated Progress:", currentProgress);

                        // Update modal fields with the project data
                        document.getElementById('project_id').value = projectId;
                        document.getElementById('project_phase').value = phase;
                        document.getElementById('currentPhaseDisplay').innerText = phase
                            .replace(/_/g, ' ') // Replaces underscores with spaces
                            .replace(/\b\w/g, c => c.toUpperCase()); // Capitalizes the first letter of each word

                        // Show the modal using Bootstrap Modal API
                        var updateModal = new bootstrap.Modal(document.getElementById('updateProjectProgressModal'));
                        updateModal.show();

                        // Handle progress dropdown: reset to null and disable past options based on currentProgress
                        const progressSelect = document.getElementById('project_phase_progress');
                        progressSelect.value = currentProgress; // Set the current progress in the dropdown

                        // Reset all options (disable, remove background color)
                        Array.from(progressSelect.options).forEach(option => {
                            option.disabled = false;
                            option.classList.remove('disabled-option');
                            option.style.backgroundColor = ''; // Reset background color
                        });

                        // Disable options from 0 up to currentProgress (inclusive)
                        Array.from(progressSelect.options).forEach(option => {
                            const progressValue = parseInt(option.value); // Parse option value

                            // Disable all options up to currentProgress
                            if (progressValue <= currentProgress) {
                                option.disabled = true; // Disable options up to the current progress
                                option.style.backgroundColor = '#d3d3d3'; // Gray background for disabled options
                            }
                        });
                    }


                    // Save the project progress
                    document.getElementById('saveProjectProgressButton').addEventListener('click', function() {
                        const saveButton = this;
                        const imageInput = document.getElementById('project_image');
                        const progressInput = document.getElementById('project_phase_progress');
                        const remarksInput = document.getElementById('project_remarks');
                        const errorMessagesDiv = document.getElementById('errorMessages');
                        const successMessageDiv = document.getElementById('successMessage');
                        let errorMessages = [];

                        // Form validation
                        if (!progressInput.value) {
                            errorMessages.push('Phase progress is required.');
                        }
                        if (!imageInput.files.length) {
                            errorMessages.push('Image is required.');
                        }

                        // Display errors if any
                        if (errorMessages.length) {
                            errorMessagesDiv.innerHTML = errorMessages.join('<br>');
                            errorMessagesDiv.style.display = 'block';
                            successMessageDiv.style.display = 'none';
                            return;
                        } else {
                            errorMessagesDiv.style.display = 'none';
                        }

                        // Change button text to "Saving Changes..."
                        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving Changes...';

                        // Prepare FormData for the AJAX request
                        const formData = new FormData(document.getElementById('updateProjectProgressForm'));

                        // Send the data using fetch
                        fetch('{{ route('progress.store') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Progress saved successfully!',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload(); // Reload the page after success
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.error || 'An error occurred. Please try again.',
                                        icon: 'error',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true
                                    });
                                }
                                saveButton.innerHTML = 'Save changes'; // Reset button text
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An unexpected error occurred. Please try again.',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                                saveButton.innerHTML = 'Save changes'; // Reset button text
                            });
                    });


                    // Show expanded image in modal
                    document.querySelectorAll('.img-preview').forEach(image => {
                        image.addEventListener('click', function() {
                            const imageUrl = this.getAttribute('data-image');
                            document.getElementById('expandedImage').src = imageUrl;
                            $('#imageModal').modal('show');
                        });
                    });
                </script>
            @endsection


            <style>
                .btn {
                    transition: background-color 0.3s, transform 0.3s;
                }

                .btn:hover {
                    transform: scale(1.05);
                }

                .hover-animation {
                    transition: background-color 0.3s;
                }

                .disabled-option {
                    background-color: #f0f0f0;
                    color: #aaa;
                    pointer-events: none;
                }

                .img-thumbnail {
                    border: none;
                    transition: transform 0.3s;
                }

                .img-thumbnail:hover {
                    transform: scale(1.1);
                }

                .table-hover tbody tr:hover {
                    background-color: #f8f9fa;
                }

                .modal-header {
                    border-bottom: none;
                }
            </style>
        @endsection
