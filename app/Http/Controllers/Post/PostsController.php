<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
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

    public function getPostData()
    {
        $posts = Posts::with('likes', 'comments')->paginate(10);
        return PostResource::collection($posts);
    }

}
