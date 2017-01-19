<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyEmailClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Email';
        $this->validValue   = 'mehmet@mkorkmaz.com';
        $this->key          = 'email';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['m@k.co'],
            ['mehmet.korkmaz@reformo.net'],
            ['mehmet+korkmaz@reformo.net']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['mehmet@korkmaz@reformo.net'], // no two @ char
            ['mehmet @mkorkmaz.com'], // no whitespace
            ['m@k.co,m@k.com'], // no comma char
            ['m@k.co;m@k.com'], // no colon char
            ['.m@k.co'], // no period at the first char
            ['m\k@k.co'], // backslash is invalid
            ['m+k.co'], // there must be at least one @ char
            ['email123456789012345678901234567890123456789012345678901234567890@k.co'] // max 64 char before @
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['mehmet,@mkorkmaz.com', 'mehmet@mkorkmaz.com', ['default' => 'mehmet@mkorkmaz.com']],
            ['m@k.co;m@k.com', null, ['default' => null]],
            ['m@k.com', 'm@k.com', ['default' => null]]
        ];
    }
}
