<?php

namespace App\Tests\Service;

use App\Handler\Parser\Product\AlzaCzParserHandler;
use App\Service\FileService;
use App\Service\ParserService;
use App\Service\ProductService;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParserServiceTest extends KernelTestCase
{
    private ManagerRegistry $registry;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->registry = self::$kernel->getContainer()->get('doctrine');
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testParseFromUrl(): void
    {
        $html = file_get_contents(
            __DIR__.'/../data/html/iPhone 15 Pro Max 256GB bílý titan - Mobilní telefon _ Alza.cz.html'
        );

        /** @var Client $clientHtml */
        $clientHtml = $this->createMock(Client::class);

        $clientHtml->expects($this->any())
            ->method('request')
            ->willReturn(new Response(200, [], $html));

        $img = file_get_contents(__DIR__.'/../data/img/RI048b2.jpg');

        /** @var Client $clientImg */
        $clientImg = $this->createMock(Client::class);

        $clientImg->expects($this->any())
            ->method('request')
            ->willReturn(new Response(200, [], $img));

        $handler = new AlzaCzParserHandler();

        $service = new ParserService(
            new FileService($clientImg),
            new ProductService($this->validator, $this->registry),
            [$handler],
            $clientHtml
        );

        $product = $service->parseFromUrl(
            'http://localhost/example',
            'alza.cz',
            __DIR__.'/../data/public/'
        );

        $this->assertSame($product->getName(), 'iPhone 15 Pro Max 256GB bílý titan');
        $this->assertSame($product->getPrice(), 35990.00);
        $this->assertSame(
            $product->getPhotoUrl(),
            'file/images/image-e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855.jpg'
        );
        $this->assertSame(
            $product->getDescription(),
            'Mobilní telefon - 6,7" Super Retina XDR OLED 2796 × 1290 (120Hz)'
        );
    }
}
