<?php

namespace Modules\User\Http\Requests;

use App\Traits\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserUpdateRequest extends FormRequest
{
    use ApiValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'sometimes|email|unique:users,email,' . $this->route('user'),
            'phone' => 'nullable|string|regex:/^\+?\d{10,15}$/|unique:users,phone,' . $this->route('user'),
            'password' => 'sometimes|string|min:8|confirmed',
        ];
    }

    /**
     * After validation hook to modify the request data.
     */
    protected function passedValidation()
    {
        if ($this->filled('password')) {
            $this->merge([
                'password' => Hash::make($this->password),
            ]);
        }
    }
}
