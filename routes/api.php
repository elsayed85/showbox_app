<?php

use App\Http\Controllers\ShowBoxController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("search" , [ShowBoxController::class , "search"]);
Route::get("top" , [ShowBoxController::class , "top"]);
Route::get("autoComplete" , [ShowBoxController::class , "autoComplete"]);

Route::get("movie/{movie}" , [ShowBoxController::class , "movie"]);
Route::get("movie/{movie}/download" , [ShowBoxController::class , "downloadMovie"]);
Route::get("movie/{movie}/srts" , [ShowBoxController::class , "movieSrts"]);

// tv
Route::get("tv/{tv}" , [ShowBoxController::class , "tv"]);
Route::get("tv/{tv}/episodes" , [ShowBoxController::class , "tvEpisodes"]);
Route::get("tv/{tv}/episodes/download" , [ShowBoxController::class , "tvEpisodesDownload"]);
Route::get("tv/{tv}/episodes/srts" , [ShowBoxController::class , "tvEpisodesSrts"]);
