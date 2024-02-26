<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'feedback_id' => $this->feedback_id,
            'parent_id' => $this->parent_id,
            'body' => html_entity_decode($this->body),
            'replies' => $this->replies
        ];
    }
}
