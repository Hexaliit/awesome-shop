<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentsController extends Controller
{
    public function index(Request $request){

        Gate::authorize('read-comment');


        $comments = Comment::all();


        if ($s = $request->input('s')){
            if ($s == 'verified'){
                $comments = Comment::query()->where('status' , '=' , '1')->get();
            } elseif ($s == 'unverified'){
                $comments = Comment::query()->where('status' , '=' , '0')->get();
            }
        }
        return response()->json([
            'comments' => $comments
        ] , 200);
    }

    public function update(Comment $comment){

        /*Gate::authorize('update-comment' , $comment);*/

        $comment->update([
            'status' => '1'
        ]);

        return response()->json(['message' => 'success']);

    }

    public function destroy(Comment $comment){

        /*Gate::authorize('delete-comment' , $comment);*/

        $comment->delete();

        return response()->json(['message' => 'success']);
    }
}
