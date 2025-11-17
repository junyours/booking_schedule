<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirm OTP</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: url('{{ asset('2.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(7px);
            z-index: 1;
        }

        .card {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            z-index: 2;
        }

        .card-header {
            background-color: #17a2b8;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #138496;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .btn-link {
            color: #17a2b8;
            text-decoration: none;

            transition: color 0.3s ease;
            margin: 10px; /* Uniform margin for spacing */
        }

        .btn-link:hover {
            color: #138496;
            border: 2px solid transparent; /* Default border */
            border-radius: 8px; /* Rounded corners */
            border-color: #17a2b8; /* Change border color on hover */
        }

        .invalid-feedback {
            color: #e3342f;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .otp-input {
            width: 40px;
            text-align: center;
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .otp-input:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 5px rgba(23, 162, 184, 0.5);
        }

        #countdown {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="backdrop"></div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header text-center">
            <h4 class="card-title" style="font-weight: 800;">{{ __('Confirm OTP') }}</h4>
        </div>
        <hr style="border-top: 1px solid #010101; margin: 0;">

        <div class="card-body px-5">
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <form id="otp-form" method="POST" action="{{ route('confirm.login.with.otp.post') }}">
                @csrf
                <div id="countdown" class="text-center mb-3" style="display: none;"></div>

                <div class="form-group mb-3 text-center">
                    <label for="otp">{{ __('Enter OTP') }}</label>
                    <div class="d-flex justify-content-center mt-3">
                        @for ($i = 0; $i < 6; $i++)
                            <input id="otp{{ $i }}" type="text"
                                class="form-control otp-input mx-1 @error('otp') is-invalid @enderror" name="otp[]"
                                maxlength="1" required>
                        @endfor
                    </div>
                    @error('otp')
                        <span class="invalid-feedback d-block text-center mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-0 text-center">
                    <button type="submit" class="btn btn-custom btn-block py-2">
                        <i class="fas fa-check-circle"></i> {{ __('Verify OTP') }}
                    </button>
                </div>

                {{-- <div id="send-another-link" class="text-center mb-3">
                    <button id="send-another-otp" class="btn btn-link text-decoration-none">
                        <i class="fas fa-paper-plane"></i> Send another OTP
                    </button>
                </div> --}}

                <div class="text-center mb-3">
                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const countdownDisplay = document.getElementById('countdown');
            const sendAnotherLink = document.getElementById('send-another-otp');
            let countdown;
            let countdownInterval;

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && index > 0 && this.value === '') {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // // Function to start the countdown
            // function startCountdown(remainingTime) {
            //     countdownDisplay.style.display = 'block';
            //     countdown = remainingTime;
            //     countdownDisplay.textContent = countdown;

            //     countdownInterval = setInterval(() => {
            //         countdown--;
            //         countdownDisplay.textContent = countdown;

            //         if (countdown <= 0) {
            //             clearInterval(countdownInterval);
            //             countdownDisplay.style.display = 'none';

            //             // Clear OTP input fields
            //             otpInputs.forEach(input => {
            //                 input.value = ''; // Reset each OTP input to empty
            //             });

            //             // Send AJAX request to clear OTP on the server
            //             fetch('{{ route('set.otp.null') }}', {
            //                     method: 'POST',
            //                     headers: {
            //                         'Content-Type': 'application/json',
            //                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                             .getAttribute('content'),
            //                     },
            //                     body: JSON.stringify({
            //                         email: "{{ request()->input('email') }}"
            //                     }) // Pass necessary data
            //                 })
            //                 .then(response => response.json())
            //                 .then(data => {
            //                     if (data.success) {
            //                         console.log('OTP cleared successfully.');
            //                     } else {
            //                         console.error(data.error);
            //                     }
            //                 })
            //                 .catch(error => console.error('Error:', error));
            //         }
            //     }, 1000);
            // }

            // // Initialize countdown
            // const countdownDuration = 180; // 180 seconds
            // const startTime = Date.now();
            // const storedTime = localStorage.getItem('otpCountdownStartTime');
            // const endTime = storedTime ? parseInt(storedTime) + countdownDuration * 1000 : null;

            // if (endTime && Date.now() < endTime) {
            //     // Resume countdown
            //     const remainingTime = Math.ceil((endTime - Date.now()) / 1000);
            //     startCountdown(remainingTime);
            // } else {
            //     // Start new countdown
            //     localStorage.setItem('otpCountdownStartTime', startTime);
            //     startCountdown(countdownDuration);
            // }

            // sendAnotherLink.addEventListener('click', function(e) {

            //     const email = "{{ request()->input('email') }}"; // Get email from the request

            //     // AJAX request to resend OTP
            //     fetch('{{ route('resend.otp') }}', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                     .getAttribute('content'),
            //             },
            //             body: JSON.stringify({
            //                 email
            //             })
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 alert(data.success);
            //                 // Reset the countdown
            //                 localStorage.setItem('otpCountdownStartTime', Date.now());
            //                 startCountdown(180); // Restart countdown for 180 seconds
            //             } else {
            //                 alert(data.error);
            //             }
            //         })
            //         .catch(error => console.error('Error:', error));
            // });
        });
    </script>

</body>

</html>
