<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page
Route::get('/', [TodoListController::class, 'index'])
    ->name('index');

Route::prefix('todo-list')->name('todo_list.')->group(function () {
    Route::get('/', [TodoListController::class, 'index'])
        ->name('index');

    Route::get('/create', [TodoListController::class, 'create'])
        ->name('create');

    Route::post('/', [TodoListController::class, 'store'])
        ->name('store');

    Route::get('/{todoList}', [TodoListController::class, 'show'])
        ->name('show');

    Route::get('/{todoList}/edit', [TodoListController::class, 'edit'])
        ->name('edit');

    Route::patch('/{todoList}', [TodoListController::class, 'update'])
        ->name('update');

    Route::delete('/{todoList}', [TodoListController::class, 'destroy'])
        ->name('destroy');
});


Route::resource('task', TaskController::class);
