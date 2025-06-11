<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => ['required', 'digits:11', 'unique:users,phone_number'],
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
            'referral_code' => ['nullable', 'string', 'max:6', 'regex:/^[\pL\pN\s\-]+$/u'],
        ];
    }

    public function messages()
    {
        return [
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ];
    }
}
