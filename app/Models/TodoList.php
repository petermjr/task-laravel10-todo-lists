<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    use HasFactory;

    public const STATE_COMPLETED = 'completed';
    public const STATE_UNCOMPLETED = 'uncompleted';

    protected $fillable = ['title', 'state'];

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function getStatusAttribute()
    {
        return Task::$states[(int) $this->state];
    }
}
