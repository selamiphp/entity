<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\PhpArray;
use Selami\Entity\Interfaces\ParserInterface;
use UnexpectedValueException;

class MyPhpArrayParserClass extends  \PHPUnit_Framework_TestCase
{
    protected  $validSchema;

    protected  $invalidSchema ;

    protected  $imaginaryFile = '/tmp/imaginary_config_file.php';


    public function setup()
    {
        $this->validSchema = dirname(__DIR__) . '/resources/config_data/config.php';
        $this->invalidSchema = [
            dirname(__DIR__) . '/resources/config_data/config_invalid.php',
            dirname(__DIR__) . '/resources/config_data/config_invalid_parse.php',
            dirname(__DIR__) . '/resources/config_data/config_invalid_type.php',
            dirname(__DIR__) . '/resources/config_data/config_invalid_division_by_zero.php',
            dirname(__DIR__) . '/resources/config_data/config_invalid_arithmetic.php',
            dirname(__DIR__) . '/resources/config_data/config_invalid_assertion.php',
            dirname(__DIR__) . '/resources/config_data/config_invalid_not_array.php'
        ];
    }

    /**
     * @test
     */
    public function shouldReturnArrayCorrectly()
    {
        $serializer = new PhpArray($this->validSchema);
        $this->assertInstanceOf(ParserInterface::class, $serializer);
        $schema = $serializer->parse();
        $this->assertArrayHasKey('schema', $schema );
        $this->assertArrayHasKey('id', $schema ['schema']);
        $this->assertArrayHasKey('age', $schema ['schema']);
        $this->assertArrayHasKey('is_active', $schema ['schema']);
        $this->assertArrayHasKey('type', $schema ['schema']['id']);
        $this->assertEquals('int', $schema ['schema']['id']['type']);
        $this->assertEquals(3, $schema ['schema']['id']['length']);
        $this->assertEquals(0, $schema ['schema']['id']['min']);
        $this->assertEquals(999, $schema ['schema']['id']['max']);
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     * @expectedException UnexpectedValueException
     * @expectedException UnexpectedValueException
     * @expectedException UnexpectedValueException
     * @expectedException UnexpectedValueException
     */
    public function shouldThrowUnexpectedValueExceptionForInvalidArray()
    {
        $serializer = new PhpArray($this->invalidSchema[0]);
        $serializer->parse();
        $serializer = new PhpArray($this->invalidSchema[1]);
        $serializer->parse();
        $serializer = new PhpArray($this->invalidSchema[2]);
        $serializer->parse();
        $serializer = new PhpArray($this->invalidSchema[3]);
        $serializer->parse();
        $serializer = new PhpArray($this->invalidSchema[4]);
        $serializer->parse();
        $serializer = new PhpArray($this->invalidSchema[5]);
        $serializer->parse();
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     *
     */
    public function shouldThrowUnexpectedValueExceptionForInvalidReturnArray()
    {
        $serializer = new PhpArray($this->invalidSchema[6]);
        $serializer->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidArray()
    {
        new PhpArray($this->imaginaryFile);
    }


    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $serializer = new PhpArray($this->validSchema);
        $isFormatOk = $serializer->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethod()
    {
        $serializer = new PhpArray($this->invalidSchema[0]);
        $isFormatOk = $serializer->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}