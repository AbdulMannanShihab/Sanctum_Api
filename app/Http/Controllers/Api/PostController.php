<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(){

        $posts = Post::all();

        return response()->json([
            'status' => true,
            'message' => 'All posts',
            'posts' => $posts
        ], 200);
    }

    public function store(Request $request){
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required'
            ]
        );
        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $img->move(public_path('images'), $imageName);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'post' => $post
        ], 200);
    }
    
}
