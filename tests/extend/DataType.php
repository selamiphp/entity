<?php
declare(strict_types=1);

namespace Selami\Test\Abstracts;

use InvalidArgumentException;

abstract class DataType extends \PHPUnit_Framework_TestCase
{
    protected $className;
    protected $validValue;
    protected $key;
    protected $options;

    /**
     * @test
     */
    public function shouldReturnDataTypeInterfaceInstance()
    {
        $dataType = new $this->className($this->key, $this->validValue, $this->options);
        $this->assertInstanceOf($this->className, $dataType);
    }

    /**
     * @test
     * @dataProvider trueProvider
     *
     * @param mixed $value
     * @param array $options
     */
    public function shouldReturnTrueForValidDataAssertion($value, array $options=[])
    {
        $dataType = new $this->className($this->key, $value, $options);
        $this->assertTrue($dataType->assert());
    }

    /**
     * @test
     * @dataProvider exceptionProvider
     *
     * @param mixed $value
     * @param array $options
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowExceptionForInvalidDataAssertion($value, array $options=[])
    {
        $dataType = new $this->className($this->key, $value, $options);
        $dataType->assert();
    }

    /**
     * @test
     * @dataProvider defaultsProvider
     *
     * @param mixed $value
     * @param array $options
     * @param array $expected
     */

    public function shouldReturnNormalizedValues($value, $expected, array $options=[])
    {
        $dataType = new $this->className($this->key, $value, $options);
        $returnedValue  =$dataType->normalize();
        $this->assertEquals($expected, $returnedValue);
    }
}
