<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:categories,id'],

            'title' => ['array'],
            'title.' . app()->getFallbackLocale() => ['required', 'string', 'max:250'],
            'title.*' => ['nullable', 'string', 'max:250'],

            'description' => ['nullable', 'array'],
            'description.*' => ['nullable', 'string'],

//            'filters' => ['nullable', 'array'],
//            'filters.*' => ['nullable', 'int', 'exists:filters,id'],

            'active' => ['bool'],

            'icon' => ['required_without:parent_id', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg'],
            'background' => ['required_without:parent_id', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg']
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
            'parent_id' => trans('Parent category'),
            'title' => trans('Title'),
            'description' => trans('Description'),
            'filters' => trans('Filters'),
            'icon' => trans('Icon'),
            'background' => trans('Background'),
            'active' => trans('Active status'),
        ];
    }
}
