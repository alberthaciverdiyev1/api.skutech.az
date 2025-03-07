<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

trait ApiValidationResponse
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errorMessages = [];
        $fields = $validator->errors()->keys();
        foreach ($validator->errors()->all() as $index => $message) {
            $errorMessages[] = [
                'field' => $fields[$index],
                'message' => $message,
            ];
        }

        $response = [
            'data' => $errorMessages,
        ];

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}
