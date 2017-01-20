<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyPhoneNumberClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\PhoneNumber';
        $this->validValue   = '+905555555555';
        $this->key          = 'phone_number';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['+905555555555'],
            ['905555555555'],
            ['+18004873217'],
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['+90555555555a'],
            ['+90555555555555555555'],
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['+90555555555a', null],
            ['+905555555555', '+905555555555'],
            ['+90555555555a', 'NaN', ['default' => 'NaN']]
        ];
    }
}
