<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskUpdateRequest extends FormRequest
{
    private string $unauthorizedMessage = 'Unauthorized action!';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        if ($this->input('todo_list_id') != $task->todoList->id) {
            return false;
        }
        if ($task->state == Task::STATE_DISABLED) {
            $this->unauthorizedMessage = 'The task is disabled!';
            return false;
        }
        if ((new \DateTime()) > (new \DateTime($task->deadline))) {    // check if current date is past task deadline
            $this->unauthorizedMessage = 'The deadline is reached!';
            return false;
        }
        return true;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json(['errors' => [0 => $this->unauthorizedMessage]], 403));
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
            'state'             => 'nullable|in:uncompleted,completed,disabled',
            'tz_offset_minutes' => 'required|integer'
        ];
    }
}
