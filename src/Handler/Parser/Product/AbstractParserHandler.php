<?php

namespace App\Handler\Parser\Product;

abstract class AbstractParserHandler implements ParserHandlerInterface
{
    protected static string $marketplace;

    public function marketplace(): string
    {
        return static::$marketplace;
    }
}
