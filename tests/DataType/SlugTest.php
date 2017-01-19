<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MySlugClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Slug';
        $this->validValue   = 'slug-slug';
        $this->key          = 'slug';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['slug'],
            ['slug-2'],
            ['slug-2-sub'],
            ['12-slug-2']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['-slug'],
            ['-slug-'],
            ['slug/slug'],
            ['www.github.com'],
            ['http:/www.github.com'],
            ['Selami Entity']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['-slug', null],
            ['slug/slug', 'NaN', ['default' => 'NaN']]
        ];
    }
}
