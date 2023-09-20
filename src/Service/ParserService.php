<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\HandlerTypeError;
use App\Handler\Parser\Product\ParserHandlerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ParserService
{
    /**
     * @param iterable<ParserHandlerInterface> $handlers
     */
    public function __construct(
        private readonly FileService $fileService,
        private readonly ProductService $productService,
        #[TaggedIterator('app.parser.handler')]
        private readonly iterable $handlers,
        private readonly Client $httpClient = new Client(),
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function parseFromUrl(
        string $url,
        string $marketplace,
        string $publicDir
    ): Product {
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            ],
        ]);
        $content = $response->getBody()->getContents();

        foreach ($this->handlers as $handler) {
            if ($marketplace === $handler->marketplace()) {
                $productDto = $handler->handle($content);

                $productDto->setPhotoUrl($this->fileService->saveImageFromUrl($productDto->getPhotoUrl(), $publicDir));

                return $this->productService->createProduct($productDto->toArray());
            }
        }

        throw new HandlerTypeError();
    }
}
