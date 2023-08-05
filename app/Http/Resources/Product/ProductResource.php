<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\Rating\RatingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name ,
            'description' => $this->description ,
            'image' => $this->image ,
            'price' => $this->price ,
            'category' => new CategoryResource($this->whenLoaded('category')),
            /*'category' => new CategoryResource($this->category),*/
            /*'category' => $this->category,*/
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            /*'comments' => $this->comments,*/
/*            'ratings' => $this->whenLoaded('ratings',
                function (){
                    return [
                        'value' => round($this->ratings->avg('value') , 1),
                        'count' => $this->ratings->count()
                    ];
                }
                ),*/
            'ratings' => round($this->ratings , 1),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'sells' => (int) $this->sells,
            'created_at' => $this->created_at
        ];
    }
}
