<?php

namespace App\Scraper;
use App\Models\Category;
use App\Models\CategoryStory;
use App\Models\Chapter;
use App\Models\Content;
use App\Models\Rate;
use App\Models\Story;
use Goutte\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\Exception\TransportException;
use Throwable;


class truyenfullVn
{
    public function scrape()
    {
        $url = 'https://truyenfull.vn/';
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $crawler->filter('ul.control.nav.navbar-nav  div.col-md-4 ul.dropdown-menu li')->each(
            function (Crawler $node) {
                $name = $node->filter('a')->attr('title');
                $url = $node->filter('a')->attr('href');
                $category = Category::where('name', $name)->first();
                if (!$category ) {
                    $category = new Category();
                    $category->name = $name;
                    $category->url = $url;
                    $category->save();
                }
            }
        );
    }

    public function scrape_story()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            for($k = 1; $k <= 1; $k++){
                $urls = [$category->url. 'trang-'.$k];
                foreach ($urls as $url) {
                    try {
                        $client = new Client();
                        try{
                            $crawler = $client->request('GET', $url);
                        }catch (TransportException $e){
                            Log::info($e);
                            return true;
                        }
                        $crawler->filter('div.col-xs-12.col-sm-12.col-md-9.col-truyen-main div.list.list-truyen.col-xs-12 div.row')->each(
                            function (Crawler $node) {
                                $name = $node->filter('h3.truyen-title a')->attr('title');
                                $url = $node->filter('h3.truyen-title a')->attr('href');
                                $author=$node->filter('span[itemprop="author"]')->text();
                                $story = Story::where('url', $url)->first();
                                if (!$story) {
                                    $story = new Story();
                                    $story->name = $name;
                                    $story->url = $url;
                                    $story->author=$author;
                                    $story->save();
                                }
                            }
                        );
                    } catch (\InvalidArgumentException $e) {
                        report($e);
                        return true;
                    }
                }
            }
        }
    }

    public function scrape_chapter()
    {
        $stories = Story::all();
        foreach ($stories as $story) {
            for ($k = 1; $k <= 100; $k++) {
                $urls = [$story->url . 'trang-' . $k . '/#list-chapter'];
                foreach ($urls as $url) {
                    try {
                        $client = new Client();
                        try {
                            $crawler = $client->request('GET', $url);
                        } catch (TransportException $e) {
                            Log::info($e);
                            return true;
                        }
                        $crawler->filter('div.col-xs-12.col-sm-6.col-md-6 li')->each(
                            function (Crawler $node) use ($story) {
                                $story_id = Story::where('url', $story->url)->value('id');
                                $name = $node->filter('a')->attr('title');
                                $url = $node->filter('a')->attr('href');
                                $chapter = Chapter::where('url', $url)->first();
                                if (!$chapter) {
                                    $chapter = new Chapter();
                                    $chapter->title = $name;
                                    $chapter->url = $url;
                                    $chapter->story_id = $story_id;
                                    $chapter->save();
                                }
                            }
                        );
                    } catch (\InvalidArgumentException $e) {
                        report($e);
                        return true;
                    }
                }
            }
        }
    }

    public function scrape_detail()
    {
        $stories = Story::all();
        foreach ($stories as $story) {
            $urls = [$story->url];
                foreach ($urls as $url) {
                        $client = new Client();
                    try {
                        $crawler = $client->request('GET', $url);
                    }catch (TransportException $e){
                        return false;
                    }
                    $crawler->filter('col-xs-12.col-info-desc')->each(
                            function (Crawler $node) use ($story) {
                                $story_id = Story::where('url', $story->url)->value('id');
                                $name = $node->filter('h3.title')->text();
                                $author = $node->filter('a[itemprop="author"]')->attr('title');
                                $rating = $node->filter('strong span[itemprop="ratingValue"]')->text();
                                $description = $node->filter('div[itemprop="description"]')->text();
                                $ratingCount=$node->filter('strong span[itemprop="ratingCount"]')->text();
                                $thumbnail=$node->filter('img[itemprop="image"]')->attr('src');
                                $rate =Rate::where('story_title', $name)->first();
                                if (!$rate) {
                                    $rate = new Rate();
                                    $rate->story_id = $story_id;
                                    $rate->story_title = $name;
                                    $rate->author = $author;
                                    $rate->thumbnail_img=$thumbnail;
                                    $rate->rating = $rating;
                                    $rate->rating_count=$ratingCount;
                                    $rate->description = $description;
                                    $rate->save();
                                }
                            }
                        );
                }
        }
        //        $chapters = Chapter::all();
//        foreach ($chapters as $chapter) {
//            $urls = array($chapter->url);
//            foreach ($urls as $url) {
//                    $client = new Client();
//                    try{
//                    $crawler = $client->request('GET', $url);
//                }catch (TransportException $e){
//                        Log::info($e);
//                        return true;
//                    }
//                    $crawler->filter('div#wrap')->each(
//                        function (Crawler $node) use ($chapter) {
//                            $chapter_id = Chapter::where('url', $chapter->url)->value('id');
//                            $content = $node->filter('div.chapter-c')->text();
//                            $chap = Content::where('chapter_id', $chapter_id)->first();
//                            if (!$chap) {
//                                $chap = new Content();
//                                $chap->content = $content;
//                                $chap->chapter_id = $chapter_id;
//                                $chap->save();
//                            }
//                        }
//
//                    );
//
//            }
//        }
//        $stories = Story::all();
//        foreach ($stories as $story) {
//                $urls = [$story->url];
//                try {
//                    foreach ($urls as $url) {
//                        try {
//                            $client = new Client();
//                            try {
//                                $crawler = $client->request('GET', $url);
//                            } catch (TransportException $e) {
//                                Log::info($e);
//                                return true;
//                            }
//                            $crawler->filter('div.info a[itemprop="genre"]')->each(
//                                function (Crawler $node) use ($story) {
//                                    $url = $node->filter('a')->attr('href');
//                                    $category_id = Category::where('url',$url )->value('id');
//                                    $story_id = Story::where('url',$story->url)->value('id');
//                                    $cate = CategoryStory::where('story_id', $story_id)->first();
//                                    $story = CategoryStory::where('category_id', $category_id)->first();
////                                    if (!$story) {
////                                        $cate_story = new CategoryStory();
////                                        $cate_story->category_id = $category_id;
////                                        $cate_story->story_id = $story_id;
////                                        $cate_story->save();
////                                    } else
////                                        if (!$story && !$cate) {
//                                        $cate_story = new CategoryStory();
//                                        $cate_story->category_id = $category_id;
//                                        $cate_story->story_id = $story_id;
//                                        $cate_story->save();
//                                    }
////                                }
//                            );
//                        } catch (\InvalidArgumentException $e) {
//                            report($e);
//                            return true;
//                        }
//                    }
//                } catch (Throwable $e) {
//                    report($e);
//                    return true;
//                }
//            }
    }
}

