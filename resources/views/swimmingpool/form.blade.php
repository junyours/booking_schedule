@extends('layouts.apps')

@section('title')
    {{ isset($service) ? 'Edit Swimming Pool Service' : 'Create Swimming Pool Service' }}
@endsection

@section('content')
    <h4>SwimmingPool Services</h4>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header"
                style="background-image: url('{{ asset('swimmingpool.jpg') }}'); height: 180px; background-size: cover; background-position: center;">
            </div>

            <div id="spinner" style="display:none;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-dismissible fade show alert-success" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-dismissible fade show alert-danger" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="swimmingpoolServiceForm"
                    action="{{ isset($service) ? route('swimmingpool-services.update', $service->id) : route('swimmingpool-services.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($service))
                        @method('PUT')
                    @endif

                    <div class="form-group mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ old('name', isset($service) ? $service->name : '') }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="design" class="form-label">Design</label>
                        @if (isset($service) && $service->design)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $service->design) }}" alt="{{ $service->name }}"
                                    style="width: 150px; height: auto; border: 1px solid #ccc;">
                            </div>
                        @endif

                        <input type="file" class="form-control-file @error('design') is-invalid @enderror" id="design"
                            name="design">
                        @error('design')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span id="designError" class="invalid-feedback" style="display: none;">Please upload a design
                            file.</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="complexity" class="form-label">Design Complexity</label>
                        <select name="complexity" class="form-select" id="complexity" required>
                            <option value="very_easy"
                                {{ old('complexity', isset($service) ? $service->complexity : '') == 'very_easy' ? 'selected' : '' }}>
                                Very Easy</option>
                            <option value="easy"
                                {{ old('complexity', isset($service) ? $service->complexity : '') == 'easy' ? 'selected' : '' }}>
                                Easy</option>
                            <option value="medium"
                                {{ old('complexity', isset($service) ? $service->complexity : '') == 'medium' ? 'selected' : '' }}>
                                Medium</option>
                            <option value="hard"
                                {{ old('complexity', isset($service) ? $service->complexity : '') == 'hard' ? 'selected' : '' }}>
                                Hard</option>
                            <option value="very_hard"
                                {{ old('complexity', isset($service) ? $service->complexity : '') == 'very_hard' ? 'selected' : '' }}>
                                Very Hard</option>
                        </select>
                    </div>

                    @if (!isset($service))
                        <input type="hidden" name="category" value="{{ $swimmingpool_id }}">
                    @endif

                    <div class="form-group mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3">{{ old('description', isset($service) ? $service->description : '') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" id="submitBtn" class="btn btn-outline-info rounded-pill px-4 py-2">
                            <i class="fas fa-save me-1"></i> <!-- Icon for 'Save' -->
                            {{ isset($service) ? 'Update Service' : 'Add Service' }}
                        </button>
                        <a href="{{ route('swimmingpool') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                            <i class="fas fa-times me-1"></i> <!-- Icon for 'Cancel' -->
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
        .floating-form {
            position: relative;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: #fff;
            border: 1px solid #ddd;
        }

        .stylish-header {
            padding: 20px;
            background: linear-gradient(135deg, #606061, #008fcc);
            /* Gradient Background */
            color: #fff;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stylish-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .stylish-header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
            transition: transform 0.5s;
        }

        .stylish-header:hover::before {
            transform: scale(1.2) translate(-10px, -10px);
        }

        .stylish-header::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
            transition: transform 0.5s;
        }

        .stylish-header:hover::after {
            transform: scale(1.2) translate(10px, 10px);
        }

        .floating-form .form-group label {
            font-weight: bold;
        }

        .floating-form .form-control {
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .floating-form .btn {
            border-radius: 4px;
            padding: 10px 20px;
        }

        .floating-form .btn-primary {
            background-color: #007bff;
            border: 1px solid #007bff;
        }

        .floating-form .btn-secondary {
            background-color: #6c757d;
            border: 1px solid #6c757d;
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
    </style>

    <script>
        document.getElementById('serviceForm').addEventListener('submit', function(event) {
            var designInput = document.getElementById('design');
            var designError = document.getElementById('designError');

            if (designInput.files.length === 0) {
                designError.style.display = 'block';
                event.preventDefault(); // Prevent form submission
            } else {
                designError.style.display = 'none';
            }
        });
    </script>
@endsection
