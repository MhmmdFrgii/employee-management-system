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
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Karyawan harus diisi.',
            'amount.required' => 'Gaji harus diisi.',
            'type' => 'Jenis transaksi harus di isi',
            'transaction_date.required' => 'Tanggal transaksi harus diisi'
        ];
    }
}
