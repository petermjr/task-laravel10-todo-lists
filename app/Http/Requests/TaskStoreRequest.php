<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
            'todo_list_id'      => 'required|integer',
            'content'           => 'required|string|min:1|max:255',
            'deadline'          => 'required|date',
            'tz_offset_minutes' => 'required|integer'
        ];
    }
}
