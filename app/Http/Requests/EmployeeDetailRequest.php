<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeDetailRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'nik' => ['required', 'string', 'min_digits:16', 'max_digits:16', Rule::unique('employee_details', 'nik')->ignore($this->employee)],
            'fullname' => ['required', 'string'],
            'photo' => ['required', 'mimes:png,jpg', 'max:2048'],
            'cv' => ['required', 'mimes:png,jpg', 'max:2048'],
            'phone' => ['required', 'numeric', Rule::unique('employee_details', 'phone')->ignore($this->employee)],
            'gender' => ['required', 'string'],
            'address' => ['required', 'string'],
            'hire_date' => ['required', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ID pengguna wajib diisi.',
            'user_id.exists' => 'ID pengguna tidak valid.',
            'department_id.required' => 'Departemen wajib diisi.',
            'department_id.exists' => 'Departemen tidak valid.',
            'position_id.required' => 'Posisi wajib diisi.',
            'position_id.exists' => 'Posisi tidak valid.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nik.min_digits' => 'NIK harus terdiri dari 16 digit.',
            'nik.max_digits' => 'NIK harus terdiri dari 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'fullname.string' => 'Nama lengkap harus berupa teks.',
            'photo.required' => 'Foto wajib diunggah.',
            'photo.mimes' => 'Foto harus berformat PNG atau JPG.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'cv.required' => 'CV wajib diunggah.',
            'cv.mimes' => 'CV harus berformat PNG atau JPG.',
            'cv.max' => 'Ukuran CV maksimal 2MB.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.numeric' => 'Nomor telepon harus berupa angka.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'gender.required' => 'Jenis kelamin wajib diisi.',
            'gender.string' => 'Jenis kelamin harus berupa teks.',
            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'hire_date.required' => 'Tanggal diterima kerja wajib diisi.',
            'hire_date.date' => 'Tanggal diterima kerja harus berupa tanggal yang valid.',
        ];
    }
}
