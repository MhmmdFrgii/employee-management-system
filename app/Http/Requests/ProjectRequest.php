<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => 'required|max:250',
            'description' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price' => 'required|not_regex:/-/',
            'department_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama proyek harus diisi.',
            'price.required' => 'Harga harus diisi.',
            'price.not_regex' => 'Harga tidak boleh negatif.',
            'name.max' => 'Nama proyek tidak boleh lebih dari 250 karakter.',
            // 'description.required' => 'Deskripsi harus diisi',
            'description.max' => 'Deskripsi tidak boleh lebih dari 250 karakter.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}
