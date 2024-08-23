<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class AssignmentRequest extends FormRequest
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
            'project_id' => 'required',
            'employee_id' => 'required',
            'role'  => 'required|max:255',
            'assigned_at' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Project_id harus di isi',
            'employee_id.required' => 'employee_id harus di isi',
            'role.required' => 'Role harus di isi',
            'role.max' => 'Maximal karakter adalah 255',
            'assigned_at.required' => 'Data harus di isi',
        ];
    }
}