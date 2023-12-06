<?php

namespace Tests\Functional\Http\Controllers\Api;

class TodoListControllerTest extends \Tests\TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function show_should_return_json()
    {
        $todoList = new \App\Models\TodoList([
            'title' => 'My task'
        ]);
        $todoList->save();
        $response = $this->get(route('api.todo_list.show', ['todoList' => $todoList->id]));
        $response->assertOk();
        $this->assertJson($response->getContent());
    }

    /**
     * @test
     * @return void
     */
    public function show_should_return_404_if_list_is_not_found()
    {
        $response = $this->get(route('api.todo_list.show', ['todoList' => 50000]));
        $response->assertNotFound();
    }
}
