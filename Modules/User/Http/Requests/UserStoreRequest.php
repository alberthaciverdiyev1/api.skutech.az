<?php

namespace Modules\User\Http\Requests;

use App\Traits\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserStoreRequest extends FormRequest
{
    use ApiValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * After validation hook to modify the request data.
     */
    protected function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}
