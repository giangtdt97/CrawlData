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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = Rate::paginate(15);
        $payload = Crypt::encrypt($data);
        return
//            RateResource::collection($data);
        response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $payload
        ],Response::HTTP_OK);
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
        $payload = Crypt::encrypt($stories);
        return
//            RateResource::collection($stories);
            response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $payload
            ],Response::HTTP_OK);
    }
}
