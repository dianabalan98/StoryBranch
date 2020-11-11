<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addComment(Request $request) {

    	$this->validate($request, array(
			'comment_body' => 'required|max:2000'
        ));
        
        $comment = new Comment;
        $comment->story_id = $request->story_id;
        $comment->node_id = $request->node_id;
        $comment->reader_id = Auth::user()->id;
        $comment->body = $request->comment_body;

        $comment->save();
        return "Salvat";
    }

    public function getComments($node_id) {
    	//return 'intra';
    	$comments = Comment::join('users', 'users.id', '=', 'comments.reader_id')
    						->where('node_id', '=', $node_id)
    						->select('comments.*', 'users.username', 'users.avatar', 'users.id AS user_id')
    						->orderBy('created_at', 'asc')
    						->get()->toArray();

    	return $comments;
    }
}
