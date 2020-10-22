<?php

namespace App\Scraper;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\DomCrawler\Crawler;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\QueryException;

class truyenfullVn
{

    public function scrape()
    {
        $url=array('https://truyenfull.vn/');
        for ($i = 0; $i < count($url); $i++) {
            $client = new Client();
                $crawler = $client->request('GET', $url[$i]);
                $crawler->filter('div.col-xs-6')->each(
                    function (Crawler $node ) {
                        $name = $node->filter('a')->text();
                        $url = $node->filter('a')->attr('href');
                        $product = DB::table('products')->where('name',$name)->count();
                        if(!$product){
                            $product = new Product();
                            $product->name = $name;
                            $product->url = $url;
                            $product->save();
                        }
                    }
                );
        }
    }
}

