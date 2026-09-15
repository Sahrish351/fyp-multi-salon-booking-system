<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|max:20|unique:users,phone',
            'password'              => 'required|string|min:8|confirmed',
            'city'                  => 'nullable|string|max:100',
            'cnic'                  => 'nullable|string|max:20',
            'salon_name'            => 'required|string|max:255',
            'address'               => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Full name is required.',
            'email.required'        => 'Email address is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'phone.required'        => 'Phone number is required.',
            'phone.unique'          => 'This phone number is already registered.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'salon_name.required'   => 'Salon name is required.',
            'address.required'      => 'Salon address is required.',
        ];
    }
}