<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyTextClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Text';
        $this->validValue   = 'PHP is a popular general-purpose scripting language '
            . 'that is especially suited to web development.';
        $this->key          = 'description';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['Selami Entity'],
            ['Selami Entity', ['max'=> 200]]
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['2323', ['min' => 5]],
            [0.1],
            ['Selami Entity', ['max'=> 10]]
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['Selami Entity', 'Selami Entity'],
            ['Selami<Entity>', 'Selami'],
            ['Selami Entity', 'Selami Entity', ['max' => 200]],
            [
                null,
                'PHP is a popular general-purpose scripting language',
                ['min' => 10, 'default' => 'PHP is a popular general-purpose scripting language']
            ],
            [
                'Selami',
                'Selami    ',
                ['min' => 10, 'default' => 'PHP is a popular general-purpose scripting language', 'pad' => 'right']
            ],
            ['Selami Entity', 'Selami Ent', ['max' => 10]],
            ["<script>alert('XSS');</script>", "alert('XSS');"]
        ];
    }
}
