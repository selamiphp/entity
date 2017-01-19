<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyEnumClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Enum';
        $this->validValue   = 'F';
        $this->key          = 'gender';
        $this->options      = ['values' => ['M','F','NaN']];
    }

    public function trueProvider()
    {
        return [
            ['M', ['values' => ['M','F','NaN']]],
            ['F', ['values' => ['M','F','NaN']]],
            [1, ['values' => [1,2,3]]],
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['E', ['values' => ['M','F','NaN']]],
            [null, ['values' => ['M','F','NaN']]],
            ['F', ['values' => ['M']]],
            ['1', ['values' => [1,2,3]]],
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['M', 'M', ['values' => ['M','F','NaN']]],
            ['F', 'F', ['values' => ['M','F','NaN']]],
            [1, 'NaN', ['values' => ['M','F','NaN'], 'default' => 'NaN']],
            ['1', 3, ['values' => [1,2,3], 'default' => 3]],
        ];
    }
}
