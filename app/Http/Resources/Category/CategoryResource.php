<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            /*'children' => CategoryCollection::collection($this->child) ,*/
            /*'parent' => new CategoryResource($this->parent) ,*/
            'title' => $this->title ,
            'status' => $this->status ,
        ];
    }
}
