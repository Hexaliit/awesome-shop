<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['category'];

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

/*    public function getRatingAttribute(Product $product){
        return $product->ratings()->avg('value');
    }*/


}
