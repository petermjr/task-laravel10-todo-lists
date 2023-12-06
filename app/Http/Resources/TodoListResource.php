<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'state'      => $this->state,
            'tasks'      => $this->tasks()->orderBy('created_at','desc')->get(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
