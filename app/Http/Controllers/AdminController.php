<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{   
    // public function verifyOtp(Request $request, $id) {
    //     $request->validate([
    //         'otp' => 'required|digits:6',
    //     ]);
        
    //     $user = User::findOrFail($id);

    //     if ($user->otp === $request->otp && $user->otp_expires_at->isFuture()) {
    //         $user->is_verified = true;
    //         $user->otp = null;
    //         $user->otp_expires_at = null;
    //         $user->save();

    //         auth()->login($user);

    //         return redirect('/')->with('success', 'Account verified and logged in');

    //     }

    //     return back()->withErrors('otp', 'Invalid or expired OTP');

    // }
    
    // public function showOtpForm($id) {
    //     $user = User::findOrFail($id);

    //     return view('home', compact('user'));
    // }

    public function sendOtp($id) {
        $user = User::findOrFail($id);

        $otp = rand(100000, 999999);

        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return back()->with('success', 'OTP sent to ' . $user->email);
    }

    public function showUsers() {
        if (!Auth::check() || !Auth::user()->isadmin) {
            return redirect('/')->with('error', 'Access denied');
        }

        $users = User::all();

        return view('home', compact('users'));
    }

    public function modifyUsers(Request $request, $id) {
        
        $user = User::findOrFail($id);

        if ($request->action === 'edit') {
            $updateData = [
                'name' => $request->updatename,
                'email' => $request->updateemail,
                'password' => bcrypt($request->updatepassword),
                'isadmin' => $request->has('updateisadmin'),
            ];

            $user->update($updateData);

            if (Auth::id() === $user->$id) {
                Auth::setUser($user);
            }

            return back()->with('success', 'User updated successfully');
        }

        if ($request->action === 'delete') {
            $user->delete();
            return back()->with('success', 'User deleted successfully');
        }

        return back()->with('error', 'Invalid');
    }

}
