<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingsController extends Controller
{
    public function store(Product $product , Request $request){
        $this->validate($request , [
            'value' => ['required' , 'integer' , 'between:1,5']
        ]);

        Rating::query()->create([
            'user_id' => auth()->user()->id,
            'product_id' => $product->id,
            'value' => $request->input('value')
        ]);

        return response()->json(['message' => 'Rating created successfully'] , 201);
    }
}
