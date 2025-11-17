@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Enter the OTP sent to your email</h2>
        <form action="{{ route('otp.verify.action') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="otp">OTP</label>
                <input type="text" class="form-control" name="otp" id="otp" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form>
    </div>
@endsection
