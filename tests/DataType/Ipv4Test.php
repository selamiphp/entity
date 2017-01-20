<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyIpv4Class extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Ipv4';
        $this->validValue   = '127.0.0.1';
        $this->key          = 'ip';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['127.0.0.1'],
            ['8.8.8.8'],
            ['192.168.0.1']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['::1'],
            ['8.8.8'],
            ['127.0.0.0.1']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['192.168.0.1', '192.168.0.1'],
            ['8.8.8.8', '8.8.8.8'],
            ['8.8.8', null]
        ];
    }
}
