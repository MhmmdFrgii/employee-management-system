<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'salary' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => 'Departemen wajib diisi.',
            'department_id.exists' => 'Departemen yang dipilih tidak ditemukan.',

            'position_id.required' => 'Posisi wajib diisi.',
            'position_id.exists' => 'Posisi yang dipilih tidak ditemukan.',

            'salary.required' => 'Gaji wajib diisi.',
            'salary.numeric' => 'Gaji harus berupa angka.',
            'salary.min' => 'Gaji tidak boleh kurang dari 0.',
        ];
    }
}
