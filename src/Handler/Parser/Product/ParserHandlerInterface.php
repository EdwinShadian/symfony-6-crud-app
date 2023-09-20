<?php

namespace App\Handler\Parser\Product;

use App\DTO\Parser\Product\ResponseDTO;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.parser.handler')]
interface ParserHandlerInterface
{
    public function handle(string $content): ResponseDTO;

    public function marketplace(): string;
}
