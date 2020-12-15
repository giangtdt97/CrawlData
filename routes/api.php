<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('categories', CategoryController::class)->only('index','show');
Route::apiResource('stories', StoryController::class)->only('index','show');
Route::apiResource('chapters', ChapterController::class)->only('index','show');
Route::apiResource('rates', RateController::class)->only('index','show');
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signup']);

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [AuthController::class,'logout']);
        Route::get('user', [AuthController::class,'user']);
    });
});
Route::post('search', [SearchController::class,'search']);
Route::get('categories/{category}/stories', [CategoryController::class ,'getStories']);
Route::get('rates/{story_id}/stories', [RateController::class ,'getStories']);
Route::get('stories/{story}/chapters', [StoryController::class ,'getChapters']);
