<?php

namespace App\Handler\Parser\Product;

use App\DTO\Parser\Product\ResponseDTO;
use Symfony\Component\DomCrawler\Crawler;

class AlzaCzParserHandler extends AbstractParserHandler
{
    protected static string $marketplace = 'alza.cz';

    public function handle(string $content): ResponseDTO
    {
        $crawler = new Crawler($content);

        $photoUrl = $crawler->filter('.detailGallery-alz-6')->first()->attr('src');
        $name = $crawler->filter('.title-share')->children()->first()->children()->first()->text();
        $priceText = $crawler->filter('.price-box__price')->text();
        $price = (float) preg_replace('/[^0-9]/', '', $priceText);
        $description = $crawler->filter('.nameextc')->children()->first()->text();

        return new ResponseDTO(
            $name,
            $description,
            $price,
            $photoUrl
        );
    }
}
