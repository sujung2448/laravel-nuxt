<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Comment;

class BoardController extends Controller
{

    public function store(Request $request)
    {
        $validation = $request-> validate([
            'title' => 'required',
            'contents' => 'required'
        ]);
        $user = auth()->user();

        $board = new Board();
        $board -> user_id = $user->id;
        $board -> title = $validation['title'];
        $board -> contents = $validation['contents'];
        $board -> save();

        return response() -> json($board);
    }

    public function list()
    {
        $boards = Board::whereNull('deleted_at')
            ->with('user')
            ->withCount('viewComment')   //null값만 가져오기 위해 모델을 따로 지정함
            ->orderBy('id', 'desc')->paginate(10);

        return response() -> json($boards);
    }
    
    public function show($id)
    {
        $board = Board::where('id', $id)-> first(); 
        $commentList = Comment::where('board_id', $id)
            ->with('user')
            ->whereNull('deleted_at') //null인것만 가져오게..
            ->orderby('created_at','desc')
            ->get();
        $data = ['board'=> $board, 'comment' => $commentList];

        return response() -> json($data);
    }
    
    public function destroy($id)
    {
        $board = Board::find( $id) 
            ->update(['deleted_at' => now()]);

        // $board -> delete();

        return response() -> json($board);
    }

    public function edit($id)
    {
        $board = Board::where('id', $id) -> first();
        
        return response() -> json($board);
    }

    public function update(Request $request, $id)
    {
        $validation = $request -> validate([
            'title' => 'required',
            'contents' => 'required'
        ]);
        

        $board = Board::where('id', $id) -> first();

        $board -> title = $validation['title'];
        $board -> contents = $validation['contents'];
        $board -> save();

        return response() -> json($board);
    }
    
    public function create()
    {
        return view('boards.create');
    }

    // public function store(Request $request)
    // {
    //     $validation = $request-> validate([
    //         'title' => 'required',
    //         'contents' => 'required'
    //     ]);

    //     $board = new Board();
    //     $board -> title = $validation['title'];
    //     $board -> contents = $validation['contents'];
    //     $board -> save();

    //     return redirect() -> route('boards.index');
    // }

    // public function show($id)
    // {
    //     $board = Board::where('id', $id) -> first();
    //     return view('boards.show', compact('board'));
    // }    
    
    // public function edit($id)
    // {
    //     $board = Board::where('id', $id) -> first();
    //     return view('boards.edit', compact('board'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $validation = $request -> validate([
    //         'title' => 'required',
    //         'contents' => 'required'
    //     ]);
        

    //     $board = Board::where('id', $id) -> first();

    //     $board -> title = $validation['title'];
    //     $board -> contents = $validation['contents'];
    //     $board -> save();

    //     return redirect() -> route('boards.show', $id);
    // }

    // public function destroy($id)
    // {
    //     $board = Board::where('id', $id) -> first();
    //     $board -> delete();

    //     return redirect() -> route('boards.index');
    // }




}