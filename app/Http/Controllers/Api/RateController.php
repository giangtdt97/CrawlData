<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Rate;
use App\Models\Story;
use Illuminate\Http\Request;
use App\Http\Resources\Rate as RateResource;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $data = Rate::paginate(15);
//        $payload = Crypt::encrypt($data);
        return
            RateResource::collection($data);
//        response()->json([
//            'status' => 200,
//            'message' => 'success',
//            'data' => $payload
//        ],Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return RateResource
     */
    public function show(Rate $rate)
    {
        return new RateResource($rate);
    }
    public function getStories($id){
        $stories = Story::findorFail($id)->rates();

        return RateResource::collection($stories);
    }
}
