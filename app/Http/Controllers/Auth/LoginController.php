<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class LoginController extends Controller
{
    /**
     * Show the application login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:60',
            'password' => 'required|min:6',
        ]);
    
        // Check if the user exists and password is correct
        $user = User::where('email', $request->email)->first();
    
        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        } else {
            // Generate and update OTP
            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addSeconds(60); // Set expiration to 60 seconds
            
            // Update the user with the new OTP and expiration time
            $user->otp = $otp;
            $user->otp_expires_at = $expiresAt; // Set the expiration time
            $user->save();
    
            // Send OTP via email
            Mail::send('emails.loginWithOTPEmail', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Login with OTP');
            });
    
            return redirect()->route('confirm.login.with.otp')->with('success', 'Check your email inbox/spam folder for the login OTP code.');
        }
    }
    

    /**
     * Handle a logout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
