<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyDoubleClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Double';
        $this->validValue   = 0.1;
        $this->key          = 'ratio';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            [0.1],
            [PHP_INT_MIN/11],
            [PHP_INT_MAX/11]
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['2323'],
            [1],
            [PHP_INT_MAX]
        ];
    }

    public function defaultsProvider()
    {
        return [
            [0.1, 0.1],
            [PHP_INT_MAX/11, PHP_INT_MAX/11],
            [9, 0.5, ['default' => 0.5]]
        ];
    }
}
