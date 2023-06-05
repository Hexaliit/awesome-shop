<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function product(){
        return $this->belongsTo(Product::class , 'product_id');
    }

    public function getVerifiedComments(){
        return Product::query()->where('status' , '1')->get();
    }

    public function getUnverifiedComments(){
        return Product::query()->where('status' , '0')->get();
    }
}
