<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyIntegerClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Integer';
        $this->validValue   = true;
        $this->key          = 'is_active';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            [0],
            [PHP_INT_MIN],
            [PHP_INT_MAX]
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['2323'],
            [0.1],
            [(PHP_INT_MAX+1)]
        ];
    }

    public function defaultsProvider()
    {
        return [
            [PHP_INT_MAX, PHP_INT_MAX],
            [9, 9],
            ['9', 0, ['default' => 0]]
        ];
    }
}
