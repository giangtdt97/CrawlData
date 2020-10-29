<?php

namespace App\Scraper;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Rate;
use App\Models\Story;
use App\Models\Test;
use Goutte\Client;
use HttpException;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\DomCrawler\Crawler;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\QueryException;

class truyenfullVn
{
    public function scrape()
    {
//        $url='https://truyenfull.vn';
//            $client = new Client();
//                $crawler = $client->request('GET', $url);
//                $crawler->filter('div.col-xs-6')->each(
//                    function (Crawler $node ) {
//                        $name = $node->filter('a')->text();
//                        $url = $node->filter('a')->attr('href');
//                        $category = DB::table('categories')->where('name',$name)->first();
//                        if(!$category){
//                            $category = new Category();
//                            $category->name = $name;
//                            $category->url = $url;
//                            $category->save();
//                        }
//                    }
//                );
        $stories = Story::all();
        foreach ($stories as $story) {
            for ($k = 0; $k < 100; $k++) {
                $url = array($story->url);
                for ($i = 0; $i < count($url); $i++) {
                    $client = new Client();
                    $crawler = $client->request('GET', $url[$i]);
                    $crawler->filter('div.col-xs-12.col-sm-12.col-md-9.col-truyen-main')->each(
                        function (Crawler $node) use ($story) {
                            $story_id = Story::where('url', $story->url)->value('id');
                            $name = $node->filter('h3.title')->text();
                            $rating = $node->filter('strong span')->text();
                            $rate = DB::table('rates')->where('story_title', $name)->first();
                            if (!$rate) {
                                $rate = new Rate();
                                $rate->story_title = $name;
                                $rate->rating = $rating;
                                $rate->story_id = $story_id;
                                $rate->save();
                            }
                        }
                    );
                }
            }
        }
    }
    public function scrape_story()
    {
        try {
            $categories=Category::all();
            foreach ($categories as $category) {
                for ($k = 0; $k < 375; $k++) {
                    $url = array($category->url . 'trang-' .$k.'/');
                    for ($i = 0; $i < count($url); $i++) {
                        $client = new Client();
                        $crawler = $client->request('GET', $url[$i]);
                        $crawler->filter('h3.truyen-title')->each(
                            function (Crawler $node)use ($category){
                                $category_id = Category::where('url', $category->url)->value('id');
                                $name = $node->filter('a')->attr('title');
                                $url = $node->filter('a')->attr('href');
                                $story = DB::table('stories')->where('url', $url)->first();
                                if (!$story) {
                                    $story = new Story();
                                    $story->name = $name;
                                    $story->url = $url;
                                    $story->category_id = $category_id;
                                    $story->save();
                                }

                            }
                        );
                    }
                }
            }
        }catch (HttpException $e) {

        }
    }
    public function scrape_chapter(){
        try {
            $stories=Story::all();
            foreach ($stories as $story) {
                for ($k = 0; $k < 100; $k++) {
                    $url = array($story->url.'trang-'. $k.'/#list-chapter');
                    for ($i = 0; $i < count($url); $i++) {
                        $client = new Client();
                        $crawler = $client->request('GET', $url[$i]);
                        $crawler->filter('div.col-xs-12.col-sm-6.col-md-6 li' )->each(
                            function (Crawler $node)use ($story){
                                $story_id = Story::where('url', $story->url)->value('id');
                                $name = $node->filter('a')->attr('title');
                                $url = $node->filter('a')->attr('href');
                                $chapter = DB::table('chapters')->where('url', $url)->first();
                                if (!$chapter) {
                                    $chapter = new Chapter();
                                    $chapter->title = $name;
                                    $chapter->url = $url;
                                    $chapter->story_id = $story_id;
                                    $chapter->save();
                                }
                            }
                        );
                    }
                }
            }
        }catch (HttpException $e) {

        }
    }
}

