<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use Selami\Entity;

class MyFunction extends TestCase
{
    /**
     * @test
     * @param $item
     * @param $expected
     * @dataProvider typeProvider
     */
    public function getDataTypeShouldReturnRightDataType($item, $expected)
    {
        $type = Entity\getDataType($item);
        $this->assertEquals($expected, $type);
    }

    public function typeProvider()
    {
        return [
            [true, 'boolean'],
            ['string', 'string'],
            [1, 'integer'],
            [PHP_INT_MAX, 'integer'],
            [7E-10, 'float'],
            [1.1, 'float'],
            [['item'], 'array'],
            [null, 'null']
        ];
    }

    /**
     * @test
     */
    public function upperCamelCaseShouldReturnRightString()
    {

        $this->assertEquals('FilePath', Entity\upperCamelCase('file_path'));
    }
}
