<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\Chapter as ChapterResource;
use Illuminate\Support\Facades\Crypt;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $data = Chapter::all();
        return  ChapterResource::collection($data);
//        $payload = Crypt::encrypt($data);
//        return response()->json([
//            'status' => 200,
//            'message' => 'success',
//            'data' => $payload
//        ],Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ChapterResource
     */
    public function store(Request $request)
    {
        $chapter = Chapter::create($request->all());

        return new ChapterResource($chapter);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ChapterResource
     */
    public function show(Chapter $chapter)
    {
        return new ChapterResource($chapter);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return bool
     */
    public function update(Request $request, Chapter $chapter)
    {
        return $chapter->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
    }
}
