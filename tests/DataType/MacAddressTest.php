<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyMacAddressClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\MacAddress';
        $this->validValue   = '22af99d09203';
        $this->key          = 'mac_address';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['28:cf:e9:4a:11:e9'],
            ['08:cf:e9:4a:11:e9']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['::1'],
            ['8:cf:e9:4a:11:e9'],
            ['127.0.0.0.1']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['28:cf:e9:4a:11:e9', '28:cf:e9:4a:11:e9'],
            ['2:cf:e9:4a:11:e9', null]
        ];
    }
}
