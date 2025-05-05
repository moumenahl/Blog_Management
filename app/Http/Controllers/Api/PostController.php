<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();

        return $this->successResponse($posts, 'تم جلب المقالات بنجاح');    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create(attributes: $request->validated());
        return $this->successResponse($post, 'Post created successfully', 201);

    }
    

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $this->successResponse($post, 'تم جلب المقال بنجاح');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
    
        return $this->successResponse($post, 'Post updated successfully');

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return $this->successResponse(null, 'تم حذف المقال بنجاح', 200);    }
}
