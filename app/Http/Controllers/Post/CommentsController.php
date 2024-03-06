<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return $this->error('', $validator->errors(), 422);
        }
        comment::create([
            'post_id' => $request->post_id,
            'comment' => $request->comment,
            'user_id' => $request->user_id
        ]);
        // Increment the likes count for the specified post
        Posts::where('id', $request->post_id)->increment('comments_count');

        return $this->successResponse([
            'message' => 'comment Successful'
        ]);
    }


    public function updateComment(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('', $validator->errors(), 422);
        }
        $comment = comment::find($commentId);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        // Update the comment content
        $comment->comment = $request->comment;
        $comment->save();

        // Return a success response
        return $this->successResponse([
            'message' => 'Comment Updated Successfully'
        ]);
    }
    public function deleteComment(Request $request, $commentId)
    {
        $comment = comment::find($commentId);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        // Delete the comment
        $comment->delete();
        return response()->json(['success' => 'Comment deleted successfully'], 200);
    }
}
