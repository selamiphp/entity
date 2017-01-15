<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Interfaces\ParserInterface;
use Selami\Entity\Parser\Json;
use InvalidArgumentException;

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
        $parser = new Json();
        $parser->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/config.json');
        $this->assertInstanceOf(ParserInterface::class, $parser);
        $schema  = $parser->parse();
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
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForInvalidJson()
    {
        $parser = new Json($this->invalidSchema);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidJson()
    {
        $parser = new Json();
        $parser->getConfigFromFile('/tmp/not_existed_config_file');
    }


    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $parser = new Json($this->validSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethod()
    {
        $parser = new Json($this->invalidSchema, false);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}