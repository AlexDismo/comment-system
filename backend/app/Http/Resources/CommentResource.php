<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'author_url' => $this->author_url,
            'parent_id' => $this->parent_id,
            'files' => $this->whenLoaded('files'),
        ];
    }
}
