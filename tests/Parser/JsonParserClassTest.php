<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Interfaces\ParserInterface;
use Selami\Entity\Parser\Json;
use UnexpectedValueException;

class MyJsonParserClass extends  \PHPUnit_Framework_TestCase
{
    protected  $validSchema = '
        {
  "schema": {
    "id": {
      "type": "int",
      "length": 3
    },
    "age": {
      "type": "int",
      "length": 3
    },
    "is_active": {
      "type": "enum",
      "options": [
        0,
        1
      ]
    }
  }
}
    ';

    protected  $invalidSchema = '
        {"schema": {[{ "type": "int", "length": 3, "min": 0, "max": 999 } } ]  }
    ';

    /**
     * @test
     */
    public function shouldReturnArrayCorrectly()
    {
        $serializer = new Json(dirname(__DIR__) . '/resources/config_data/config.json', true);
        $this->assertInstanceOf(ParserInterface::class, $serializer);
        $schema  = $serializer->parse();
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
     */
    public function shouldThrowUnexpectedValueExceptionForInvalidJson()
    {
        $serializer = new Json($this->invalidSchema, false);
        $serializer->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidJson()
    {
        new Json($this->validSchema, true);
    }


    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $serializer = new Json($this->validSchema, false);
        $isFormatOk = $serializer->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethod()
    {
        $serializer = new Json($this->invalidSchema, false);
        $isFormatOk = $serializer->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}