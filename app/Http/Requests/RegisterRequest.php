<?php

namespace App\Http\Requests;

use App\Services\EmailValidationService;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi',

            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah terdaftar',

            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email)),
            'name' => trim($this->name),
        ]);
    }

    protected function passedValidation(): void
    {
        // Validasi email dengan service
        $emailValidator = app(EmailValidationService::class);
        $validation = $emailValidator->validate($this->email);

        if (! $validation['valid']) {
            $this->failedValidation(
                validator(
                    $this->all(),
                    ['email' => ['email']],
                    ['email' => $validation['message']]
                )
            );
        }
    }
}
