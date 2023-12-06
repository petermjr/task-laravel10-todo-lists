<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const STATE_UNCOMPLETED = 'uncompleted';
    public const STATE_COMPLETED = 'completed';
    public const STATE_DISABLED = 'disabled';

    protected $fillable = [
        'content', 'state', 'deadline', 'todo_list_id'
    ];

    public function todoList(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }
}
