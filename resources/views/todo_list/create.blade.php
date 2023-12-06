@extends('layouts.user')

@section('content')
    <div class="row">
        <div class="col-4 ms-auto me-auto">
            <div class="card">
                <div class="card-header">
                    <span>Create a Todo List</span>
                </div>
                <div class="card-body">
                    <form action="{{route('todo_list.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
