<?php

namespace Tests\Integration\Http\Controllers;


use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return array[]
     * @todo Again, the content length should be in static variable in Task model
     */
    public static function contentDataProvider(): array
    {
        return [
            'Empty content'                      => ['', false],
            'Content longer than 255 characters' => [str_repeat('b', 256), false],
            'Content exactly 255 characters'     => [str_repeat('b', 255), true],
        ];
    }

    public static function taskDataProvider(): array
    {
        return [
            'Past due task should not be updated' => [Task::STATE_UNCOMPLETED, '2021-12-22T05:01', false],
            'Incomplete task should be updated'   => [Task::STATE_UNCOMPLETED, '2023-12-24T05:01', true],
            'Disabled task should not be updated' => [Task::STATE_DISABLED, '2023-12-23T05:01', false],
        ];
    }

    /**
     * @test
     * @return void
     */
    public function store_should_create_new_task()
    {
        $todoList        = new TodoList();
        $todoList->title = 'Test Todo List';
        $todoList->save();

        $data = [
            'todo_list_id'      => $todoList->id,
            'content'           => 'Test Task',
            'deadline'          => '2023-12-23T05:01',  // html datetime-local input format
            'tz_offset_minutes' => 0,
        ];

        $response = $this->post(route('task.store'), $data);

        $this->assertDatabaseHas('tasks', [
            'todo_list_id' => $data['todo_list_id'],
            'content'      => $data['content'],
            'deadline'     => $data['deadline'],
        ]);
    }

    /**
     * @test
     * @dataProvider contentDataProvider
     */
    public function store_request_input_validation($content, $shouldPass)
    {
        $todoList        = new TodoList();
        $todoList->title = 'Test Todo List';
        $todoList->save();

        $data = [
            'todo_list_id'      => $todoList->id,
            'content'           => $content,
            'deadline'          => '2023-12-23T05:01',
            'tz_offset_minutes' => 0,
        ];

        $request = new TaskStoreRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertEquals($shouldPass, $validator->passes());
        if (!$shouldPass) {
            $this->assertContains('content', $validator->errors()->keys());
        }
    }

    /**
     * @return void
     * @todo Could use a dataProvider to provide new TodoList and new Task
     * @test
     */
    public function update_should_update_existing_task()
    {
        // Create new list
        $todoList        = new TodoList();
        $todoList->title = 'Test Todo List';
        $todoList->save();

        // Create a task in the list
        $task               = new Task();
        $task->content      = 'Test Task';
        $task->deadline     = '2023-12-23T05:01';
        $task->state        = Task::STATE_UNCOMPLETED;
        $task->todo_list_id = $todoList->id;
        $task->save();

        // New task data
        $data = [
            'todo_list_id'      => $todoList->id,
            'content'           => 'Updated Task',
            'deadline'          => '2023-12-24T05:01',
            'state'             => Task::STATE_COMPLETED,
            'tz_offset_minutes' => 0,
        ];

        // Update the task
        $response = $this->patch(route('task.update', ['task' => $task->id]), $data);

        // Reload the task from db
        $task->refresh();

        // Check if task was updated
        $this->assertEquals($data['content'], $task->content);
        $this->assertEquals($data['state'], $task->state);

        // Reload list from db
        $todoList->refresh();

        // Since all the (1) tasks are completed, the list state should be completed
        // @todo (instead In the controller action) we could use observer to check when task is updated
        //      and update the TodoList to completed if all tasks in the list are completed
        //      (but the drawback is that it won't be
        //          very visible looking at the controller action, what is updating it
        //              (from point of view of new developer...).
        //      also there should be a scheduled task in Kernel to update all lists to completed that match the criteria
        //          of all tasks in the list are completed
        $this->assertEquals(TodoList::STATE_COMPLETED, $todoList->state);
    }

    /**
     * @test
     * @dataProvider taskDataProvider
     */
    public function update_should_update_task($state, $deadline, $shouldPass)
    {
        // Create new list
        $todoList        = new TodoList();
        $todoList->title = 'Test Todo List';
        $todoList->save();

        // Create a task in the list
        $task               = new Task();
        $task->content      = 'Test Task';
        $task->deadline     = $deadline;
        $task->state        = $state;
        $task->todo_list_id = $todoList->id;
        $task->save();

        // New task data
        $data = [
            'todo_list_id'      => $todoList->id,
            'content'           => 'Updated Task',
            'deadline'          => '2023-12-24T05:01',
            'state'             => Task::STATE_COMPLETED,
            'tz_offset_minutes' => 0,
        ];

        // Try to update the task
        $response = $this->patch(route('task.update', ['task' => $task->id]), $data);

        // Check the response status
        if ($shouldPass) {
            $response->assertStatus(200);
        } else {
            $response->assertStatus(403);
        }

        // Reload the task from db
        $task->refresh();

        // Check if task was updated
        if ($shouldPass) {
            $this->assertEquals($data['content'], $task->content);
            $this->assertEquals($data['state'], $task->state);
        } else {
            $this->assertNotEquals($data['content'], $task->content);
            $this->assertNotEquals($data['state'], $task->state);
        }
    }
}
