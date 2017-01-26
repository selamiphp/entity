<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\ConfigIni;
use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Selami\Entity\Parser\ParserFactory;

class MyParserFactory extends TestCase
{

    /**
     * @test
     * @dataProvider parserTypeProvider
     * @param string $value
     * @param string $expected
     */
    public function shouldReturnArrayCorrectly(string $value, string $expected)
    {
        $parser = ParserFactory::createParser($value);
        $this->assertInstanceOf($expected, $parser);
    }

    public function parserTypeProvider()
    {
        return [
            ['ini', ConfigIni::class],
            ['ini', ParserInterface::class]
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForInvalidConfigIni()
    {
        ParserFactory::createParser('html');
    }

}
