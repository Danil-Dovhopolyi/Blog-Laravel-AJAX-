<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postList = Post::paginate(10);
        return response()->json(
            $postList
        , 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(StorePostRequest $request)
    {
        $post = Post::create($request->all());
        return response()->json([
            'status'=>true,
            'message'=>'Post created successfully',
            'report'=>$post
        ], 200);
    }
//    private function createPostByUser($Post) {
//         $post['creator_id'] = Auth::user()->id;
//         Post::create($post);
//     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $post = Post::find($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, $id)
    {
      $this->authorize('update', $post);
      $post = Post::find($id);
      $post->update($request->all());
        return response()->json([
            'status'=>true,
            'message'=>'Post updated successfully',
            'Post'=>$post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $this->authorize('delete', $post);
         $post = Post::findOrFail($id);
         $post->delete();

        return response()->json(['success' => true]);
    }
  
    
}
