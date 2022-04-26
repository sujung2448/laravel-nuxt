<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Log;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validation = $request-> validate([
            'comment' => 'required',
            'boardId' => 'required|exists:App\Models\Board,id' //board_id를 받음
        ]);
        $user = auth()->user();

        $comment = new Comment();
        $comment -> user_id = $user->id;
        $comment -> comment = $validation['comment'];
        $comment -> board_id = $validation['boardId'];
        $comment -> save();

        return response() -> json($comment);
    }

    // public function list()
    // {
    //     $comment = Comment::whereNull('deleted_at')
    //         ->with('user')
    //         ->with('board')
    //         ->orderBy('id', 'desc')->paginate(10);
        
    //         return response() -> json($comment);
          
    // }

    public function commentDelete($id)
    {
        $comment = Comment::where('id', $id)->first()
            ->update(['deleted_at' => now()]);
        // $comment -> deleted_at = now();
        // $comment -> save();
        // $comment -> delete();

        return response() -> json("success");
    }

    public function commentUpdate(Request $request, $id)
    {
      Log::info($request->all());

        $validation = $request -> validate([
            'comment' => 'required',
        ]);
        

        $comment = Comment::where('id', $id) -> first();

        $comment -> comment = $validation['comment'];
        $comment -> save();

        return response() -> json($comment);
    }

}
