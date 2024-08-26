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
}
