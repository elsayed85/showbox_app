<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutocompleteRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\TopSearchsRequest;
use Elsayed85\ShowBox\Api\Episode;
use Elsayed85\ShowBox\Api\Movie;
use Elsayed85\ShowBox\Api\Search;
use Elsayed85\ShowBox\Api\TV;
use Illuminate\Http\Request;

class ShowBoxController extends Controller
{
    public function search(SearchRequest $request)
    {
        config()->set('lara-showbox.default.lang' , $request->header('Accept-Language'));
        // childmode
        config()->set('lara-showbox.default.childmode' , $request->header('Child-Mode'));
        $search = Search::get(
            $type = $request->input('type'), // movie, tv, all
            $title = $request->input('title'),
            $page = $request->input('page', 1),
            $pagelimit = $request->input('pagelimit', 10)
        );

        return response()->json($search['data']);
    }

    public function top(TopSearchsRequest $request)
    {
        $search = Search::top(
            $type = $request->input('type'), // movie, tv
        );

        return response()->json($search['data']);
    }

    public function autoComplete(AutocompleteRequest $request)
    {
        $search = Search::autocomplate(
            $title = $request->input('title'),
            $pagelimit = $request->input('pagelimit', 10)
        );

        return response()->json($search['data']);
    }

    public function movie($movie_id)
    {
        $movie = Movie::get($movie_id);
        return response()->json($movie['data']);
    }

    public function downloadMovie($movie_id)
    {
        config()->set('lara-showbox.default.server' , 'showbox');
        $movie = Movie::download($movie_id)['data']['list'];
        // filter where path is not empty
        $movie = array_filter($movie , function($item){
            return !empty($item['path']);
        });
        $movie = array_values($movie);
        return response()->json($movie);
    }

    public function movieSrts($movie_id)
    {
        $subs = Movie::srts($movie_id);
        if(isset($subs['data'] , $subs['data']['list'])){
            $list = $subs['data']['list'];
            // filter only language Arabic
            $list = array_filter($list , function($sub){
                return $sub['language'] == 'Arabic';
            });
            $list = array_values($list);
            // pick subtitles[file_path] of each item
            $list = array_map(function($sub){
                return $sub['subtitles'];
            } , $list);
            $list = array_values($list)[0];
            // pick file_path of each item
            $list = array_map(function($sub){
                return $sub['file_path'];
            } , $list);
            return response()->json($list);
        }
        return response()->json([]);
    }

    public function tv($tv_id)
    {
        $tv = Tv::get($tv_id);
        return response()->json($tv['data']);
    }

    public function tvEpisodes(Request $request ,  $tv_id)
    {
        $tv = Episode::all($tv_id , $request->input('season' , 1) );
        return response()->json($tv['data']);
    }

    public function tvEpisodesDownload(Request $request ,  $tv_id)
    {
        config()->set('lara-showbox.default.server' , 'showbox');
        $tv = Episode::donwload($tv_id , $request->input('season' , 1) , $request->input('episode' , 1) );
        $tv = $tv['data']['list'];
        // filter where path is not empty
        $tv = array_filter($tv , function($item){
            return !empty($item['path']);
        });
        $tv = array_values($tv);
        return response()->json($tv);
    }

    public function tvEpisodesSrts(Request $request ,  $tv_id)
    {
        $subs = Episode::srts($tv_id , $request->input('season' , 1) , $request->input('episode' , 1) );
        if(isset($subs['data'] , $subs['data']['list'])){
            $list = $subs['data']['list'];
            // filter only language Arabic
            $list = array_filter($list , function($sub){
                return $sub['language'] == 'Arabic';
            });
            $list = array_values($list);
            // pick subtitles[file_path] of each item
            $list = array_map(function($sub){
                return $sub['subtitles'];
            } , $list);
            $list = array_values($list)[0];
            // pick file_path of each item
            $list = array_map(function($sub){
                return $sub['file_path'];
            } , $list);
            return response()->json($list);
        }
        return response()->json([]);
    }
}
