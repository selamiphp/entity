<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyIpv6Class extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Ipv6';
        $this->validValue   = '127.0.0.1';
        $this->key          = 'ip';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['::1'],
            ['2002:0:0:0:0:0:808:808']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['127.0.0.1'],
            ['8.8.8'],
            ['2002:0:0:0:0:0:808:808:0']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['::1', '::1'],
            ['2002:0:0:0:0:0:808:808', '2002:0:0:0:0:0:808:808'],
            ['2002:0:0:0:0:0:808', null]
        ];
    }
}
