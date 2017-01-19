<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyBooleanClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Boolean';
        $this->validValue   = true;
        $this->key          = 'is_active';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            [true],
            [false]
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['2323'],
            ['13'],
            ['03.0']
        ];
    }

    public function defaultsProvider()
    {
        return [
            [true, true],
            [false, false],
            [1, false, ['default' => false]]
        ];
    }
}
