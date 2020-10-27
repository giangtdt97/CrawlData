<?php

namespace App\Scraper;
use App\Models\Category;
use App\Models\Story;
use Goutte\Client;
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
        $url=array('https://truyenfull.vn');
        for ($i = 0; $i < count($url); $i++) {
            $client = new Client();
                $crawler = $client->request('GET', $url[$i]);
                $crawler->filter('div.col-xs-6')->each(
                    function (Crawler $node ) {
                        $name = $node->filter('a')->text();
                        $url = $node->filter('a')->attr('href');
                        $category = DB::table('categories')->where('name',$name)->first();
                        if(!$category){
                            $category = new Category();
                            $category->name = $name;
                            $category->url = $url;
                            $category->save();
                        }
                    }
                );
        }
    }
    public function scrape_story()
    {
        $categories=Category::all();
        foreach ($categories as $category) {
            for ($k = 0; $k < 400; $k++) {

                $url = array($category->url . 'trang-' . $k);

                for ($i = 0; $i < count($url); $i++) {
                    $client = new Client();
                    $crawler = $client->request('GET', $url[$i]);
                    $crawler->filter('h3.truyen-title')->each(
                        function (Crawler $node) {
                            $category_id = Category::where('url', 'https://truyenfull.vn/the-loai/tien-hiep/')->value('id');
                            $name = $node->filter('a')->text();
                            $url = $node->filter('a')->attr('href');
                            $story = DB::table('stories')->where('name', $name)->first();
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
    }
    public function scrape_chapter(){}
}

