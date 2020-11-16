<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\Story as StoryResource;
use App\Http\Resources\Chapter as ChapterResource;
use Illuminate\Support\Facades\Crypt;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $data = Story::paginate(10);
        return StoryResource::collection($data);
//        $payload = Crypt::encrypt($data);
//        response()->json([
//            'status' => 200,
//            'message' => 'success',
//            'data' => $payload
//        ],Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return StoryResource
     */
    public function store(Request $request)
    {
        $story = Story::create($request->all());

        return new StoryResource($story);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return StoryResource
     */
    public function show(Story $story)
    {
        return new StoryResource($story);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return bool
     */
    public function update(Request $request, Story $story)
    {
        return $story->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Story $story)
    {
        $story->delete();
    }
    public function getChapters($id){
        $chapters = Story::find($id)->chapters()->get();

        return ChapterResource::collection($chapters);
    }
}
