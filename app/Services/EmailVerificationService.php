<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationService
{
    public function sendVerificationEmail(User $user): void
    {
        $user->notify(new VerifyEmail);
    }

    public function verifyEmail(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        return $user->markEmailAsVerified();
    }

    public function isEmailVerified(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function resendVerificationEmail(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw new \Exception('Email sudah diverifikasi sebelumnya.');
        }

        $this->sendVerificationEmail($user);
    }
}
