<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'client';
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'nik' => [
                'required',
                'digits:16',
                'regex:/^[0-9]{6}[0-1][0-9]{1}[0-3][0-9]{1}[0-9]{4}$/',
                Rule::unique('documents')->where(function ($query) {
                    return $query->where('status', '!=', 'completed')
                        ->where('status', '!=', 'rejected');
                }),
            ],
            'kk' => ['required', 'digits:16'],
            'document_type_id' => ['required', 'exists:document_types,id,is_active,1'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            // The below fields are common but can be adjusted based on document types
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'religion' => ['nullable', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'nik.regex' => 'The NIK must be in the correct format (16 digits with proper regional and birth date structure).',
            'nik.unique' => 'There is already an active document request with this NIK. Please wait for the current request to be processed.',
        ];
    }
}
