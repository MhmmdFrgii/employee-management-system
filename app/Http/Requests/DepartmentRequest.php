<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'name' => 'required|unique:departments,name|max:255',
            'description' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Nama departemen harus diisi.',
            'name.unique' => 'Nama departemen sudah ada.',
            'description.required' => 'Deskripsi harus diisi'
        ];
    }
}
