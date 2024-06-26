<?php

namespace App\Http\Resources\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category=$this->category;
        return [
            "id" => $this->id,
            "intro" => $this->intro,
            "title" => $this->title,
            "slug" => $this->slug,
            "body" => $this->body,
            "thumbnail" => $this->thumbnail,
            "category"=>$this->category,
            "author"=>$this->author
        ];
    }
}