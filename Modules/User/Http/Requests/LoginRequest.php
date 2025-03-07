<?php

namespace Modules\User\Http\Requests;

use App\Traits\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use ApiValidationResponse;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'password' => 'required|string',
        ];
    }
}
