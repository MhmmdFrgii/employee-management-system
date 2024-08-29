<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
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
            'employee_id' => 'required',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Karyawan harus diisi.',
            'amount.required' => 'Gaji harus diisi.',
            'payment_date.required' => 'Tanggal harus diisi'
        ];
    }
}
