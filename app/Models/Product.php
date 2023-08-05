<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*protected $with = ['category'];*/

    /*protected $with = ['commentsConfirmed'];*/


    public function orderItems(){
        return $this->hasMany(OrderItems::class);
    }


    public function category(){
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function confirmedComments(){
        return $this->comments()->where('status' ,'1')->get();
    }

    public function images() : MorphMany
    {
        return $this->morphMany(Image::class , 'imageable');
    }

/*    public function getRatingAttribute(Product $product){
        return $product->ratings()->avg('value');
    }*/


}
