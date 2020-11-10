<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Story as StoryResource;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function search(Request $request){
        $request->validate([
            'query' => 'required',
        ]);

        $query = $request->input('query');
        $stories = Story::where('name', 'like', "%$query%")
            ->orWhere('url', 'like', "%$query%")
            ->get();

         return  StoryResource::collection($stories);
    }
}
