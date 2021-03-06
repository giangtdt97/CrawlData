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
use function PHPUnit\Framework\isEmpty;

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
                    Category::updateOrInsert(['name'=>$name,'url'=>$url]);
                }
            }
        );
    }

    public function scrape_story()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $this->scrape_story_by_category($category);
        }
    }

    /**
     * @param Category $category
     * @return bool|void
     */
    public function scrape_story_by_category($category)
    {
        for ($k = 1; $k <= 9999; $k++) {
            $client = new Client();
            try {
                $crawler = $client->request('GET', 'https://truyenfull.vn/danh-sach/truyen-moi/' . 'trang-' . $k);
            } catch (TransportException $e) {
                Log::info($e);
                return true;
            }
            $flagFound = new \stdClass();
            $flagFound->val = false;
            $crawler->filter('div.col-xs-12.col-sm-12.col-md-9.col-truyen-main div.list.list-truyen.col-xs-12 div.row')->each(
                function (Crawler $node) use ($flagFound, $category) {
                    if ($flagFound->val) {
                        return;
                    }

                    $name = $node->filter('h3.truyen-title a')->attr('title');
                    $url = $node->filter('h3.truyen-title a')->attr('href');
                    $author = $node->filter('span[itemprop="author"]')->text();
                    $story = Story::where('url', $url)->first();

                    if ($story) {
                        $flagFound->val = true;
                        return;
                    }

                    $story = new Story();
                    $story->name = $name;
                    $story->url = $url;
                    $story->author = $author;
                    $story->save();
                }
            );

            if ($flagFound->val) {
                return;
            }
        }
    }

    public function scrape_chapter()
    {
        $stories = Story::whereNotNull('thumbnail_img')->get();
        foreach ($stories as $story) {
            for ($k = 1; $k <= 101; $k++) {
                $client = new Client();
                try {
                    $crawler = $client->request('GET', $story->url . 'trang-' . $k . '/#list-chapter');
                } catch (TransportException $e) {
                    Log::info($e);
                    return true;
                }
                $flagFound = new \stdClass();
                $flagFound->val = false;
                $crawler->filter('div.col-xs-12.col-sm-6.col-md-6 li')->each(
                    function (Crawler $node) use ($flagFound,$story) {
                        if ($flagFound->val) {
                            return;
                        }
                        $story_id = Story::where('url', $story->url)->value('id');
                        $name = $node->filter('a')->attr('title');
                        $url = $node->filter('a')->attr('href');
                        $chapter = Chapter::where('url', $url)->first();
                        if ($chapter) {
                            $flagFound->val = true;
                            return;
                        }

                        $chapter = new Chapter();
                        $chapter->title = $name;
                        $chapter->url = $url;
                        $chapter->story_id = $story_id;
                        $chapter->save();

                    }
                );
            }
        }
    }

    public function scrape_detail()
    {
        $stories = Story::whereNull('thumbnail_img')->get();
        foreach ($stories as $story) {
            try {
                $client = new Client();
                $crawler = $client->request('GET', $story->url);
            } catch (TransportException $e) {
                Log::info($e);
                return true;
            }
            $crawler->filter('div.col-xs-12.col-info-desc')->each(
                function (Crawler $node) use ($story) {
                    $story_id = Story::where('url', $story->url)->value('id');
                    $name = $node->filter('h3.title')->text();
                    if ($author = $node->filterXPath('a[itemprop="author"]')->count()) {
                        $author = $node->filterXPath('a[itemprop="author"]')->attr('title');
                    }
                    $rating = $node->filter('strong span[itemprop="ratingValue"]')->text();
                    $description = $node->filter('div[itemprop="description"]')->html();
                    $ratingCount = $node->filter('strong span[itemprop="ratingCount"]')->text();
                    $thumbnail = $node->filter('img[itemprop="image"]')->attr('src');
                    $rate = Rate::where('id', $story_id)->first();
                    if (!$rate) {
                        $rate = new Rate();
                        $rate->story_id = $story_id;
                        $rate->story_title = $name;
                        $rate->author = $author;
                        $rate->thumbnail_img = $thumbnail;
                        $rate->rating = $rating;
                        $rate->rating_count = $ratingCount;
                        $rate->description = $description;
                        $rate->save();
                    }
                    Story::where('id', $story_id)->whereNull('thumbnail_img')->update(['thumbnail_img' => $thumbnail]);
                }
            );
        }
    }
    public function scrape_chapter_content()
    {
        $chapters = Chapter::whereNull('content')->get();
        foreach ($chapters as $chapter) {
            try {
                $client = new Client();
                $crawler = $client->request('GET', $chapter->url);
            }catch (TransportException $e) {
                Log::info($e);
                return true;
            }
            $crawler->filter('div#wrap')->each(
                function (Crawler $node) use ($chapter) {
                    $chapter_id = Chapter::where('url', $chapter->url)->value('id');
                    $content = $node->filter('div.chapter-c')->html();
                    Chapter::where('id', $chapter_id)->whereNull('content')->update(['content'=>$content]);
                }

            );
        }
    }
    public function scrape_pivot_table(){
        $stories = Story::whereNotNull('thumbnail_img')->get();
        foreach ($stories as $story) {
            $client = new Client();
            try {
                $crawler = $client->request('GET', $story->url);
            } catch (TransportException $e) {
                Log::info($e);
                return true;
            }
            $crawler->filter('div.info a[itemprop="genre"]')->each(
                function (Crawler $node) use ($story) {
                    $url = $node->filter('a')->attr('href');
                    $category_id = Category::where('url',$url )->value('id');
                    $story_id = Story::where('url',$story->url)->value('id');
                    CategoryStory::updateOrInsert(['category_id'=>$category_id,'story_id'=>$story_id]);
                                    }
                            );
                    }
    }
}

