<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Reply;
use App\Comment;
use App\Http\Resources\Reply as ReplyResource;

class CommentRepliesController extends Controller
{
    public function index($commentId) {
        $comment = Comment::find($commentId);

        return ReplyResource::collection($comment->replies);

    }

    public function store(Request $request) {
        $validator = $this->validate($request, [
            'text' => 'required|max:200',
        ]);

        $reply = new Reply();
        $reply->user_id = $request->input('user_id');
        $reply->comment_id = $request->input('comment_id');
        $reply->text = $request->input('text');

        $return = ['success' => 'Reply Create Failed', 'reply_id' => $reply->id];
        if($reply->save()) {
            $return = ['success' => 'Reply Created', 'reply_id' => $reply->id];
        }
        return response()->json($return);
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);

        $return = ['success' => 'Reply Delete Failed'];
        if ($reply->delete()) {
            $return = ['success' => 'Reply Deleted'];
        }
        return response()->json($return);
    }
}
