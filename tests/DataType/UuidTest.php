<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;

class MyUuidClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Uuid';
        $this->validValue   = 'TextTest.php';
        $this->key          = 'description';
        $this->options      = [];
    }

    public function trueProvider()
    {
        return [
            ['9507c888-2186-443d-8e4a-9bee412be869'],
            ['7b2dcd95-13a2-46e9-ac31-7b2772d63404']
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['7b2dcd95-13a2-46e9-ac31-7b2772d6340'],
            ['b2dcd95-13a2-46e9-ac31-7b2772d63404'],
            ['7g2dcd95-13a2-46e9-ac31-7b2772d63404'],
            ['random-text']
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['9507c888-2186-443d-8e4a-9bee412be869', '9507c888-2186-443d-8e4a-9bee412be869'],
            ['00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000'],
            ['b2dcd95-13a2-46e9-ac31-7b2772d63404', '00000000-0000-0000-0000-000000000000']
        ];
    }
}
