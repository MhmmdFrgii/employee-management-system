<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',  // Validasi company_id
            'employee_id' => 'required|numeric',
            'start_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'type' => 'required|string|max:255',
            // 'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'ID karyawan harus diisi.',
            'employee_id.numeric' => 'ID karyawan harus berupa angka.',

            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau lebih lambat.',
            'start_date.before_or_equal' => 'Tanggal mulai tidak boleh lebih dari tanggal selesai.',

            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',

            'type.required' => 'Jenis cuti harus diisi.',
            'type.string' => 'Jenis cuti harus berupa teks.',
            'type.max' => 'Jenis cuti tidak boleh lebih dari 255 karakter.',

            'status.required' => 'Status harus diisi.',
        ];
    }
}
