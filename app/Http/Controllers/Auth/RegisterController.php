<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'sipa' => 'required|string|max:100',
            'apotek_address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Wrap the creation of User and Notification inside a Database Transaction
        $user = \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'sipa' => $validated['sipa'],
                'apotek_address' => $validated['apotek_address'],
                'password' => Hash::make($validated['password']),
            ]);

            // Automatically initialize standard notification settings for the new user
            Notification::create([
                'user_id' => $user->id,
                'batas_minimal_stok' => 10,
                'waktu_restock_hari' => 7,
                'is_active' => true,
            ]);

            return $user;
        });

        // Login user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang di MedLogix.');
    }
}
