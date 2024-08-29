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
            'fullname' => 'required|string|regex:/^[a-zA-Z\s\'.-]+$/|max:255',
            'nik' => 'required|string|regex:/^[0-9]+$/|max:20|unique:employee_details,nik',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|regex:/^[0-9]+$/|max:15|unique:employee_details,phone',
            'address' => 'required|string|max:500',
            'hire_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:approve,disapprove,rejected', // For the status field in the User model
        ];
    }
}
