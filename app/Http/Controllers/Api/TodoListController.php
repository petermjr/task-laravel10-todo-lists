<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;

class TodoListController extends Controller
{
    public function show(TodoList $todoList)
    {
        return new TodoListResource($todoList);
    }
}
