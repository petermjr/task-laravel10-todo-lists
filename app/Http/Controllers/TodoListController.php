<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListStoreRequest;
use App\Http\Requests\TodoListUpdateRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todoLists = TodoList::all();
        return view('todo_list.index', compact('todoLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todo_list.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoListStoreRequest $request)
    {
        try {
            $todoList = new TodoList($request->validated());
            $todoList->save();
            return redirect(route('todo_list.show', ['todoList' => $todoList]));
        } catch (\Exception $exception) {
            report($exception);
            return back()->with(['error' => 'There was a problem creating the Todo List']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TodoList $todoList)
    {
        return view('todo_list.show', compact('todoList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoListUpdateRequest $request, TodoList $todoList)
    {
        try {
            $data            = $request->validated();
            $todoList->title = $data['title'];
            $todoList->save();
            return new TodoListResource($todoList);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There was a problem updating the Todo List']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoList $todoList)
    {
        $todoList->delete();
    }
}
