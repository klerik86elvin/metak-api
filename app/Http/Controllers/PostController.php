<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::paginate(5));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return $post;
    }
    public function create(CreatePostRequest $request)
    {
        $data = [
            'title' => $request->validated()['title'],
            'text' => $request->validated()['text'],
        ];

        $post = Post::create($data);
        $post->update([
            'image' => $request->image->store('posts','public')
        ]);
        return response()->json(['status' =>  'success']);
    }
    public function auth(Request $request)
    {
        if ($request->password == env('CREATE_POST_PASSWORD'))
        {
            return response()->json(['auth' => true, 'password' => env('CREATE_POST_PASSWORD')]);
        }
        else
            return response()->json(['auth' => false, 'message' => 'Password incorrect']);
    }
}
