<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyFilePathClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\FilePath';
        $this->validValue   = 'path/file.jpg';
        $this->key          = 'path';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['path/to/file.jpg'],
            ['path-2'],
            ['/etc'],
            ['path-2/file.jpg'],
            ['path-2/path-3'],
            ['path-2/path-3/pa_th']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['file/to/path:'],
            ['file/t o/path']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['-slug', '-slug'],
            ['slug/slug:', 'NaN', ['default' => 'NaN']]
        ];
    }
}
