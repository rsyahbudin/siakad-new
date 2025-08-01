<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            // Attempt authentication with rate limiting
            $request->authenticate();

            $request->session()->regenerate();

            // Add success message
            return redirect()->intended('/dashboard')->with('success', __('login berhasil'));
        } catch (ValidationException $e) {
            // Handle rate limiting errors
            if (str_contains($e->getMessage(), 'throttle')) {
                return back()->withErrors([
                    'email' => __('auth.throttle', [
                        'seconds' => $e->getMessage(),
                        'minutes' => ceil($e->getMessage() / 60),
                    ]),
                ])->withInput($request->only('email'));
            }

            // Handle authentication failures with more specific messages
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => __('email tidak ditemukan'),
                ])->withInput($request->only('email'));
            }

            // If user exists but password is wrong
            return back()->withErrors([
                'password' => __('password salah'),
            ])->withInput($request->only('email', 'remember'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', __('logout berhasil'));
    }
}
