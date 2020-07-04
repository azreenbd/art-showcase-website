<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Comment;

class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $artwork_id)
    {
        // Data validation
        $validate = $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
            'artwork_id' => ['required', 'numeric'],
        ]);

        if($validate && $artwork_id == $request['artwork_id']) {
            $user = Auth::user();
            $comment = new Comment;

            $comment->user_id = $user->id;
            $comment->artwork_id = $request['artwork_id'];
            $comment->comment = $request['comment'];

            $comment->save();

            return redirect()->to(url()->previous() . '#' . $comment->id);
        }
        else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($artwork_id, $id)
    {
        $user = Auth::user();
        $comment = Comment::find($id);

        if($comment->artwork_id == $artwork_id && $comment->user_id == $user->id) {
            $comment->delete();

            return redirect()->to(url()->previous() . '#comment');
        } 
        else {
            return redirect()->to(url()->previous() . '#comment');
        }
    }
}
