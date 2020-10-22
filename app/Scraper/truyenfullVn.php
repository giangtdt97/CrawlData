<?php

namespace App\Scraper;
use App\Models\Product;
use Goutte\Client;
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
                        try {
                        $name = $node->filter('a')->text();
                        $url = $node->filter('a')->attr('href');
                            $product = new Product();
                            $product->name = $name;
                            $product->url = $url;
                            $product->save();
                        }catch (Illuminate\Database\QueryException $e) {
                            $errorCode = $e->errorInfo[1];
                            if ($errorCode == 1062) {
                                // houston, we have a duplicate entry problem
                            }
                        }
                    }
                );
        }
    }
}

