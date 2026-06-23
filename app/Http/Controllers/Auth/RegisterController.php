<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(private EmailVerificationService $emailVerificationService) {}

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $this->emailVerificationService->sendVerificationEmail($user);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan periksa email Anda untuk verifikasi.');
    }
}
