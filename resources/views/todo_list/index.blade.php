@extends('layouts.user')

@section('content')
    <div id="app">
        <div class="row mb-4">
            <div class="col-12 justify-content-end d-flex">
                <a class="btn btn-primary" href="{{route('todo_list.create')}}">Create new Todo List</a>
            </div>
        </div>
        @if(!$todoLists->count())
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        There are no lists.
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @foreach($todoLists as $todolist)
                <div class="col-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="d-flex justify-content-between">
                                <a href="{{route('todo_list.show', ['todoList'=>$todolist])}}">
                                    {{$todolist->title}}
                                </a>
                                <span>
                                    {{$todolist->state==\App\Models\TodoList::STATE_COMPLETED ? 'Completed' : ''}}
                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div>
                                Tasks:
                                <span>
                                    {{$todolist->tasks->count()}}
                                </span>
                            </div>
                            <div>
                                Created at (UTC):
                                <span>
                                    {{$todolist->created_at}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
