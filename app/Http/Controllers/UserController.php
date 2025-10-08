<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function home() {
        if(Auth::check() && Auth::user()->isadmin) {
            $users = User::all();
            return view('home', compact('users'));
        }
        return view('home');
    }

    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        if (Auth::attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])) {
            // $user = Auth::user();

            // $otp = rand(100000, 999999);
            // $user->otp = $otp;
            // $user->otp_expires_at = Carbon::now()->addMinutes(5);
            // $user->save();
            
            // Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

            // Auth::logout();

            // return redirect()->route('verify.otp.form', ['user_id' => $user->id])
            //                  ->with('success', 'OTP sent to your email. Please verify to continue.');
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->with('error', 'Invalid login');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout Successful');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', 
        ],
        ], ['password.regex' => 'Password must be at least 8 character long, include 1 captial, 1 symbol and 1 number',]);

        // $otp = rand(100000, 999999);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $incomingFields['isadmin'] = $request->has('isadmin');
        // $incomingFields['otp'] = $otp;
        // $incomingFields['otp_expires_at'] = Carbon::now()->addMinutes(5);
        // $incomingFields['is_verified'] = false;

        $user = User::create($incomingFields);

        // Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return redirect('/')->with('success', 'Registration Successful');
    }
}

