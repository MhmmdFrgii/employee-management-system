<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
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
            'name' => [
                'required',
                'max:255',
                Rule::unique('positions', 'name')->ignore($this->route('position')),
            ],
            'description' => 'nullable|string'
        ];
    }

    /**
     * Get the validation error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama jabatan harus diisi.',
            'name.unique' => 'Nama jabatan sudah ada.',
            'name.max' => 'Nama jabatan tidak boleh lebih dari 255 karakter.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 500 karakter.',
        ];
    }
}
