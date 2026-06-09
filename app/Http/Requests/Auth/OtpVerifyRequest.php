<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'otp'   => 'required|digits:6',
            'phone' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'otp.required'      => 'OTP code is required.',
            'otp.digits'        => 'OTP must be exactly 6 digits.',
            'phone.required'    => 'Phone number is required.',
        ];
    }
}