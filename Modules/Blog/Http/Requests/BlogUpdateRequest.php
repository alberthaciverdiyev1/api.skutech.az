<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Setting\Helpers\SettingsHelper;

class BlogUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['array'],
            'title.' . app()->getFallbackLocale() => ['required', 'string', 'max:250'],
            'title.*' => ['nullable', 'string', 'max:250'],

            'description' => ['nullable', 'array'],
            'description.*' => ['nullable', 'string'],

            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:' . SettingsHelper::maxUploadSize()],

            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp,svg'],

            'active' => ['bool'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'active' => $this->has('active'),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => trans('Title'),
            'description' => trans('Description'),

            'image' => trans('Image'),
            'gallery' => trans('Gallery'),

            'active' => trans('Active status'),
        ];
    }
}
