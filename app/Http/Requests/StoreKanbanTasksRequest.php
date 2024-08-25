<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKanbanTasksRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:50'],
            'description' => ['required', 'max:255'],
            'status' => ['required', 'in:To Do,In Progress,Done'],
            'kanban_boards_id' => ['required', 'exists:kanban_boards,id'],
            'date' => ['required'],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title harus diisi!',
            'title.string'  => 'Title tidak valid!',
            'title.max' => 'Maximal 50 karakter.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.max' => 'Maximal 255 karakter.',
            'status.required' => 'Status tidak boleh kosong.',
            'status.in' => 'Status harus berisi salah satu dari: To Do, In Progress, Done.',
            'kanban_boards_id.required' => 'Kanban Board tidak boleh kosong.',
            'kanban_boards_id.exists' => 'Kanban Board tidak valid.',
            'date.required' => 'Tanggal tidak boleh kosong.',
        ];
    }
}
