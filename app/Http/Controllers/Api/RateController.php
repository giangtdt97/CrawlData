<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Rate;
use App\Models\Story;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\Rate as RateResource;
use Illuminate\Support\Facades\Crypt;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $data = Rate::where('rating','>=',8.0)->paginate(20);
        $getResource=RateResource::collection($data);
        $payload = Crypt::encrypt($getResource);
        return $getResource;
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
        $stories = Story::find($id)->rates()->get();
        $getResource=RateResource::collection($stories);
        $payload = Crypt::encrypt($getResource);
        return $getResource;
//            response()->json([
//                'status' => 200,
//                'message' => 'success',
//                'data' => $payload
//            ],Response::HTTP_OK);
    }
    public function getBestRating(){
        $data =Rate::where('rating','>=',8.0)->get();
        return RateResource::collection($data);
    }
}
