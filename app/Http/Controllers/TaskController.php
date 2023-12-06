<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TodoListResource;
use App\Models\Task;
use App\Models\TodoList;
use App\Services\DateTimeService;

class TaskController extends Controller
{
    public function __construct(private DateTimeService $dateTimeService)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            $data = $request->validated();

            // convert deadline from user's tz to utc
            $deadline = $this->dateTimeService->convertDateTimeFromUserTzToUTC(
                $data['deadline'],
                $data['tz_offset_minutes']
            );

            /** @var TodoList|null $todoList */
            $todoList = TodoList::query()->findOrFail($data['todo_list_id']);
            $todoList->tasks()->save(new Task([
                'todo_list_id' => $data['todo_list_id'],
                'content'      => $data['content'],
                'deadline'     => $deadline
            ]));
            return new TodoListResource($todoList);
        } catch (\Exception $exception) {
            report($exception);
            return response(status: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        try {
            $data = $request->validated();

            // convert deadline from user's tz to utc
            $deadline = $this->dateTimeService->convertDateTimeFromUserTzToUTC(
                $data['deadline'],
                $data['tz_offset_minutes']
            );

            $task->deadline = $deadline;
            $task->content  = $data['content'];
            $task->state    = $data['state'];
            $task->save();

            // if all tasks are completed, mark the list as completed
            $todoList = $task->todoList;
            if ($todoList->tasks->count() == $todoList->tasks()->where('state', Task::STATE_COMPLETED)->count()) {
                $todoList->state = TodoList::STATE_COMPLETED;
                $todoList->save();
            } elseif ($todoList->state == TodoList::STATE_COMPLETED) {
                $todoList->state = TodoList::STATE_UNCOMPLETED;
                $todoList->save();
            }

            return new TodoListResource($todoList);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json([
                'error' => 'There was a problem with updating the task!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
