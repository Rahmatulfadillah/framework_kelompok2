<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmailValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailValidationController extends Controller
{
    public function __construct(private EmailValidationService $emailValidationService) {}

    public function validate(Request $request): JsonResponse
    {
        $email = $request->string('email')->trim();

        if (! $email) {
            return response()->json([
                'valid' => false,
                'message' => 'Email harus diisi',
            ]);
        }

        $validation = $this->emailValidationService->validate($email);
        $suggestion = null;

        if (! $validation['valid']) {
            $suggestion = $this->emailValidationService->getSuggestion($email);
        }

        return response()->json([
            'valid' => $validation['valid'],
            'message' => $validation['message'],
            'type' => $validation['type'],
            'suggestion' => $suggestion,
        ]);
    }
}
