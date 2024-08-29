<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KanbanBoardRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'projects_id' => ['required', 'exists:projects,id'],
            'description' => ['required', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi!asd',
            'name.string' => 'Nama tidak valid!',
            'name.max' => 'Maximal 50 karakter',
            'projects_id.required' => 'Projek tidak boleh kosong',
            'projects_id.exists' => 'Projek tidak valid',
            'description.required' => 'Deskripsi harus diisi',
            'description.max' => 'Maaximal 255 karakter',
        ];
    }
}
