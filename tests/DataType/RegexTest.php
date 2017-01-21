<?php
declare(strict_types=1);

namespace tests;

use Selami\Test\Abstracts\DataType;
use InvalidArgumentException;

class MyRegexClass extends DataType
{

    public function setUp()
    {
        $this->className    = '\\Selami\\Entity\\DataType\\Regex';
        $this->validValue   = 'valid_regex_text';
        $this->key          = 'field';
        $this->options      = ['regex' => '#^v#'];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowExceptionForEmptyRegexOption()
    {
        new $this->className($this->key, $this->validValue, []);
    }

    public function trueProvider()
    {
        return [
            [
                'https://www.youtube.com/watch?v=1VVj1zqbWpU',
                ['regex' => '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#']
            ]
        ];
    }

    public function exceptionProvider()
    {
        return [
            ['not_found_text', ['regex' => '#^t#']],
            ['not_found_text', ['regex' => '#^#t#']]
        ];
    }

    public function defaultsProvider()
    {
        return [
            ['-slug', '-slug', ['regex' => '#^\-#']],
            ['slug/slug:', 'NaN', ['default' => 'NaN', 'regex' => '#^t#']]
        ];
    }
}
