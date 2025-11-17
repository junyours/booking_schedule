<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* Global Styles */
        body {
            background: url('{{ asset('2.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
        }

        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            z-index: 1;
            animation: fadeIn 1s ease-in-out;
        }

        /* Card Styles */
        .card {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 2;
            animation: slideUp 0.8s ease-out;
            position: relative;
        }

        .card-header {
            background-color: #17a2b8;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        /* Logo Styles */
        .card-header .logo {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: bounceIn 1s ease;
        }

        .card-header .logo img {
            max-width: 60px;
            height: auto;
        }

        .form-control {
            border-radius: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 10px rgba(23, 162, 184, 0.5);
        }

        .btn-custom {
            background-color: #17a2b8;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 10px;
            color: white;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #138f99;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .invalid-feedback {
            color: #e3342f;
        }

        label {
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* Hover Effects for Links */
        .card-body a.btn-link:hover,
        .card-body p a:hover {
            color: #17a2b8;
            text-decoration: underline;
            transform: scale(1.05);
            transition: color 0.3s ease, text-decoration 0.3s ease, transform 0.3s ease;
            display: inline-block;
        }

        /* Spinner Overlay */
        .spinner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            border-radius: 15px;
        }

        .spinner-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Modern Spinner Styles */
        .modern-spinner {
            width: 70px;
            height: 70px;
            position: relative;
            display: inline-block;
        }

        .double-bounce1,
        .double-bounce2 {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #17a2b8;
            opacity: 0.6;
            position: absolute;
            top: 0;
            left: 0;
            animation: bounce 2.0s infinite ease-in-out;
        }

        .double-bounce2 {
            animation-delay: -1.0s;
        }

        @keyframes bounce {
            0%, 100% {
                transform: scale(0.0);
            }
            50% {
                transform: scale(1.0);
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0% {
                transform: translateX(-50%) translateY(-100px);
                opacity: 0;
            }
            60% {
                transform: translateX(-50%) translateY(10px);
                opacity: 1;
            }
            80% {
                transform: translateX(-50%) translateY(-5px);
            }
            100% {
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>
</head>

<body>

      <div class="backdrop"></div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="spinner-overlay" id="spinnerOverlay">
            <div class="d-flex flex-column align-items-center">
                <div class="spinner-grow text-info" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="text-info mt-2">Please Wait...</span>
            </div>
        </div>

        <div class="card-header text-center">
            <div class="logo">
                <img src="{{ asset('arfil_logo1.png') }}" alt="Logo">
            </div>
            <h4 class="card-title mt-3">{{ __('Login') }}</h4>
        </div>
        <hr style="border-top: 1px solid #010101; margin: 0;">

        <div class="card-body px-5">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">

                @csrf

                <div class="form-group mb-3">
                    <label for="email">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required placeholder="Enter your password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <div class="form-group mb-0 text-center">
                    <button type="submit" class="btn btn-custom py-2 font-weight-bold" id="submitBtn">
                        {{ __('Login') }}
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="form-group mt-3 text-center">
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                @endif

                <div class="form-group mt-3 text-center">
                    <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>

    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="d-flex flex-column align-items-center">
            <div class="spinner-grow text-info" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="text-info mt-2">Please Wait...</span>
        </div>
    </div>
    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                // Show spinner
                $('#spinnerOverlay').addClass('active');
            });
        });
    </script>
</body>

</html>
