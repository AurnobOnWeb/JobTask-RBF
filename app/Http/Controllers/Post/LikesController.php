<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\likes;
use App\Models\Posts;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{
    use HttpResponses;
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
}
