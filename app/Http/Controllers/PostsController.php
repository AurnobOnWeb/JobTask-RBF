<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\comment;
use App\Models\likes;
use App\Models\Posts;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    use HttpResponses;
    public function storePosts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|required',
            'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return $this->error('', $validator->errors(), 422);
        }
        $imagePaths = [];
        // Loop through each uploaded image
        foreach ($request->file('images') as $image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move('backend/assets/images', $imageName);
            // Store the path of each uploaded image
            $imagePaths[] = $imageName;
        }
        $post = Posts::create([
            'description' => $request->description,
            'images' => implode(',', $imagePaths), // Store image paths as comma-separated string
            'user_id' => $request->user_id
        ]);
        return $this->successResponse([
            'message' => 'Post created successfully'
        ]);
    }
    public function likes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return $this->error('', $validator->errors(), 422);
        }
        // Check if the user has already liked the post
        $existingLike = likes::where('post_id', $request->post_id)
            ->where('user_id', $request->user_id)
            ->exists();
        if ($existingLike) {
            return $this->error('', 'You Have already Liked this', 422);
            ;
        }
        likes::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user_id
        ]);
        // Increment the likes count for the specified post
        Posts::where('id', $request->post_id)->increment('likes_count');

        return $this->successResponse([
            'message' => 'Like Successful'
        ]);
    }

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
        $comment = Comment::find($commentId);
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
        $comment = Comment::find($commentId);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        // Delete the comment
        $comment->delete();
        return response()->json(['success' => 'Comment deleted successfully'], 200);
    }

    public function getPostData()
    {
        $posts = Posts::with('likes', 'comments')->paginate(10);
        return PostResource::collection($posts);
    }

}
