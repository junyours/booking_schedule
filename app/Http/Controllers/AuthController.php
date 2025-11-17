<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
 
class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }
 
    public function registerSave(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:8'
    ]);

    // If validation fails, return errors as JSON
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // Create the user
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Return a JSON response with success message and redirect URL
    return response()->json([
        'success' => 'Registration successful!',
        'redirect' => route('login')
    ]);
}
 
    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            
            if (Auth::user()->usertype === 'admin') {
                return redirect()->route('dashboard');
            } else {
                
                return redirect('/');
            }
        }
        
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function loginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);

            // Store the OTP and user ID in the session
            Session::put('otp', $otp);
            Session::put('user_id', $user->id);

            // Send OTP to user's email
            Mail::to($user->email)->send(new SendOtpMail($otp));

            // Redirect to OTP verification page
            return redirect()->route('otp.verify');
        } else {
            return back()->withErrors(['error' => 'Invalid email or password']);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
    
        // Get the OTP and user ID from the session
        $sessionOtp = Session::get('otp');
        $userId = Session::get('user_id');
    
        if ($sessionOtp == $request->otp) {
            // Find the user by ID and log them in
            $user = User::find($userId);
    
            if ($user) {
                // Log the user in
                Auth::login($user);
    
                // Clear the OTP and user ID from the session
                Session::forget('otp');
                Session::forget('user_id');
    
                // Clear the otp_expires_at field in the database
                $user->otp_expires_at = null;
                $user->save();
    
                return redirect()->route('dashboard')->with('success', 'Login successful!');
            }
    
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }
    }
    
    
    
    public function logout(Request $request)
    {
        Auth::logout(); 
    
        $request->session()->invalidate(); 
    
        return redirect('/');
    }
}