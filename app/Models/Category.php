<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['products'];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function child(){
        return $this->hasMany(Category::class , 'category_id');
    }

    public function parent(){
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function getHasChildAttribute(){
        return $this->child()->count() > 0 ;
    }

    public function getHasProductsAttribute(){
        return $this->products()->count() > 0 ;
    }

    public function getSubChildrenCategory(){
        $childrenIds = $this->child()->pluck('id');

        return Product::whereIn('category_id' , $childrenIds)->get();

    }
}
