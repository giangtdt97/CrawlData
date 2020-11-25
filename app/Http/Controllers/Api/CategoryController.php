<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Resource_;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Story as StoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $data = Category::paginate(15);
//        $payload = Crypt::encrypt($data);
        return
            CategoryResource::collection($data);
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
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }
    public function getStories($id){
        $stories = Category::findorFail($id)->stories()->paginate(15);

        return StoryResource::collection($stories);
    }

}
