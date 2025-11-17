<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function loginwithotppost(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:60'
        ]);
    
        // Check if the user exists
        $checkUser = User::where('email', $request->email)->first();
        if (is_null($checkUser)) {
            return redirect()->back()->with('error', 'Your email address is not associated with us.');
        } else {
            // Generate a new OTP
            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addMinutes(120);
    
            // Update the user with the new OTP and expiration time
            $checkUser->update([
                'otp' => $otp,
                'otp_expires_at' => $expiresAt // Set the new expiration time
            ]);
    
            // Send OTP via email
            Mail::send('emails.loginWithOTPEmail', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Login with OTP - markejano');
            });
    
            return redirect()->route('confirm.login.with.otp')->with('success', 'Check your email inbox/spam folder for the login OTP code.');
        }
    }
    
    public function confirmloginwithotppost(Request $request)
    {
        // Validate the OTP array
        $request->validate([
            'otp' => 'required|array|size:6',
            'otp.*' => 'numeric|digits:1',
        ]);
    
        // Concatenate OTP input fields
        $otp = implode('', $request->input('otp'));
    
        // Verify OTP and expiration
        $checkUser = User::where('otp', $otp)->first();
    
        if (is_null($checkUser)) {
            return redirect()->back()->with('error', 'The OTP you provided is incorrect.');
        } elseif (Carbon::now()->greaterThan($checkUser->otp_expires_at)) {
            // OTP has expired
            User::where('email', $checkUser->email)->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);
            return redirect()->back()->with('error', 'The OTP has expired. Please request a new one.');
        } else {
            // Clear OTP and log the user in
            User::where('otp', $otp)->update([
                'otp' => null,
                'otp_expires_at' => null,

            ]);
    
            Auth::login($checkUser);
            session()->flash('alert', 'You have successfully logged in!');

            // Route based on user role
            if ($checkUser->usertype === 'admin') {
                return redirect()->route('dashboard'); // Adjust the route name if needed
            }
    
            return redirect()->route('welcome');
        }
    }
    
    
            public function setOtpNull(Request $request)
        {
            $request->validate([
                'email' => 'required|email|max:60',
            ]);

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->otp = null; // Set OTP to null
                $user->otp_expires_at = null; // Optionally, set expiration to null
                $user->save();

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'error' => 'User not found.']);
        }

    

    public function resendOtp(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:60'
        ]);
    
        // Check if the user exists
        $checkUser = User::where('email', $request->email)->first();
        if (is_null($checkUser)) {
            return response()->json(['error' => 'Your email address is not associated with us.'], 404);
        }
    
        // Generate and update new OTP
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addSeconds(60); // Set new expiration time
        $checkUser->update([
            'otp' => $otp,
            'otp_expires_at' => $expiresAt // Update expiration time
        ]);
    
        // Send new OTP via email
        Mail::send('emails.loginWithOTPEmail', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Login with OTP - markejano');
        });
    
        return response()->json(['success' => 'New OTP sent to your email.']);
    }
    
}
