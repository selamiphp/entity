<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyUrlClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Url';
        $this->validValue   = 'http://github.com';
        $this->key          = 'www';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['http://github.com'],
            ['http://www.github.com'],
            ['https://github.com'],
            ['https://github.com/selamiphp'],
            ['https://github.com/selamiphp/entity'],
            ['https://github.com/selamiphp/entity/search?utf8=%E2%9C%93&q=php'],
            ['https://username@github.com/selamiphp/entity'],
            ['https://username:password@github.com/selamiphp/entity'],
            ['ssh://username:password@github.com/selamiphp/entity'],
            ['mailto:noreply@github.com'],
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['www.github.com'],
            ['http:/www.github.com'],
            ['Selami Entity']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['www.github.com', null],
            ['www.github.com', 'NaN', ['default' => 'NaN']]
        ];
    }
}
