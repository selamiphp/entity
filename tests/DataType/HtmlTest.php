<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyHtmlClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Html';
        $this->validValue   = 'PHP is a popular general-purpose scripting language '
            . 'that is especially suited to web development.';
        $this->key          = 'description';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['Selami Entity3'],
            ['Selami Entity']
        ];
    }

    public function exceptionProvider()
    {
        return [
            [0.1],
            [['random_text']]
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['Selami Entity', 'Selami Entity'],
            ['Selami<Entity>', 'Selami<Entity>'],
            ['Selami Entity', 'Selami Entity'],
            [
                null,
                'PHP is a popular general-purpose scripting language',
                ['default' => 'PHP is a popular general-purpose scripting language']
            ],
            [
                '<span>Selami</span>',
                '<span>Selami</span>',
                ['default' => 'PHP is a popular general-purpose scripting language']
            ]
        ];
    }
}
