<?php

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyDateClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Date';
        $this->validValue   = '1979-03-18';
        $this->key          = 'created_at';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['1979-03-18', ['format' => 'Y-m-d']],
            ['1996-02-29', ['format' => 'Y-m-d']], // leap year: year is evenly divisible by 4
            ['2000-02-29', ['format' => 'Y-m-d']],  // leap year: year is evenly divisible by 4, 100 but also 400
            ['1979-03-18 13:30:00', ['format' => 'Y-m-d H:i:s']],
            ['13:30:00', ['format' => 'H:i:s']],
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['1979-13-18', ['format' => 'Y-m-d']],
            [2016, ['format' => 'Y-m-d']],
            ['1979-03-18', ['format' => 'Y-m-d', 'min' => '1980-01-01']],
            ['1979-03-18', ['format' => 'Y-m-d', 'max' => '1880-01-01']],
            ['1979-03-18', ['format' => 'Y-m-d H:i:s']],
            ['1979-03-18 24:00:00', ['format' => 'Y-m-d H:i:s']],
            ['1979-03-18 23:60:00', ['format' => 'Y-m-d H:i:s']],
            ['1979-03-18 23:59:60', ['format' => 'Y-m-d H:i:s']],
            ['1979-03-32', ['format' => 'Y-m-d']],
            ['1979-4-30', ['format' => 'Y-m-d']],
            ['2000-02', ['format' => 'Y-m-d']],
            ['1900-02-29', ['format' => 'Y-m-d']] // not leap year: year is evenly divisible by 4 and 100
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['now', date('Y-m-d'), ['format' => 'Y-m-d']],
            ['1979-03-30', '1979-03-30', ['format' => 'Y-m-d', 'default' => 'now']],
            ['2000-02-19', '2000-02-19', ['format' => 'Y-m-d', 'default' => 'now']],
            ['1900-02-29', '1979-03-18', ['format' => 'Y-m-d', 'default' => '1979-03-18']]
        ];
    }
}
