<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'user_id' => $this->user_id,
            'comments' => CommentResource::collection($this->comments),
            'likes' => LikeResource::collection($this->likes),
        ];
    }
}
