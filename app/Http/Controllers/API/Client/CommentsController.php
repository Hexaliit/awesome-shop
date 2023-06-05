<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function store(Request $request , Product $product){
/*        $validated = Validator::make($request->all() , ['content' => 'required|min:5|max:200']);

        if ($validated->fails()){
            return response()->json($validated->errors() , 500);
        }*/

        $this->validate($request , [
            'content' => ['required' , 'min:5' , 'max:200' , 'string'],
        ]);



        Comment::query()->create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'content' => $request->input('content')
        ]);

        return response()->json([
            'message' => 'Comment created successfully'
        ] , 201);


    }
}
