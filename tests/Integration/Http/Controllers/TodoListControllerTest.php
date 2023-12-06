<?php

namespace Tests\Integration\Http\Controllers;


use App\Http\Requests\TodoListStoreRequest;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * @todo The max/min length of the title should be set as constant in the TodoList model
 */
class TodoListControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function store_should_create_new_todo_list()
    {
        $data = [
            'title' => str_repeat('a', 64),
        ];

        $response = $this->post(route('todo_list.store'), $data);
        // check db has the newly created todo list
        $this->assertDatabaseHas('todo_lists', $data);
        // retrieve the todo list
        $todoList = TodoList::query()->where('title', $data['title'])->first();
        // Check if we are redirecting to the newly created todo list
        $response->assertStatus(302);
        $response->assertRedirect(route('todo_list.show', ['todoList' => $todoList->id]));
    }

    /**
     * @todo Use @dataProvider to test different inputs.
     * @test
     * @return void
     */
    public function store_should_throw_validation_error_if_title_is_unsupported_length()
    {
        $faker = \Faker\Factory::create();

        $data = [
            'title' => str_repeat('a', 65),
        ];

        $request = new TodoListStoreRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('title', $validator->errors()->keys());
    }

    /**
     * @test
     * @return void
     */
    public function update_should_update_existing_todo_list()
    {
        $todoList = new TodoList();
        $todoList->title = 'Initial Title';
        $todoList->save();

        $data = [
            'title' => str_repeat('b', 64),
        ];

        $response = $this->patch(route('todo_list.update', ['todoList' => $todoList->id]), $data);

        $this->assertDatabaseHas('todo_lists', [
            'id' => $todoList->id,
            'title' => $data['title'],
        ]);
    }
}
