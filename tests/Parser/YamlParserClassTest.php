<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\Yaml;
use Selami\Entity\Interfaces\ParserInterface;

class MyYamlParserClass extends \PHPUnit_Framework_TestCase
{
    protected $validSchema = '
schema:
  id:
    type: int
    length: 3
    min: 0
    max: 999
  age:
    type: int
    length: 3
    min: 0
    max: 122
  is_active:
    type: enum
    length: 1
    options:
      - 0
      - 1
';

    protected $invalidSchema = '
schema:
  id:
    type: int
    length: 3
    min: 0
    max: 999
  age:
    type: int
    length: 3
    min: 0
    max: 122
  is_active:
     type: enum
    length: 1
    options:
      - 0
      - 1
';

    /**
     * @test
     * @expectedException UnexpectedValueException
     */
    public function shouldThrowExceptionForInvalidParserType()
    {
        new Yaml('nan');
    }

    /**
     * @test
     */
    public function shouldReturnArrayCorrectlyExt()
    {
        $parser = new Yaml('ext');
        $parser->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/config.yaml');
        $this->assertInstanceOf(ParserInterface::class, $parser);
        $schema  = $parser->parse();
        $this->assertArrayHasKey('schema', $schema);
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
    public function shouldThrowInvalidArgumentExceptionForInvalidYamlExt()
    {
        $parser = new Yaml('ext', $this->invalidSchema);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidYamlExt()
    {
        $parser = new Yaml('ext');
        $parser->getConfigFromFile('/tmp/not_existed_config_file');
    }

    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethodExt()
    {
        $parser = new Yaml('ext', $this->validSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodExt()
    {
        $parser = new Yaml('ext', $this->invalidSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }



    /**
     * @test
     */
    public function shouldReturnArrayCorrectlySymfony()
    {
        $parser = new Yaml('symfony');
        $parser->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/config.yaml');
        $this->assertInstanceOf(ParserInterface::class, $parser);
        $schema  = $parser->parse();
        $this->assertArrayHasKey('schema', $schema);
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
    public function shouldThrowInvalidArgumentExceptionForInvalidYamlSymfony()
    {
        $parser = new Yaml('symfony', $this->invalidSchema);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidYamlSymfony()
    {
        $parser = new Yaml('symfony');
        $parser->getConfigFromFile('/tmp/not_existed_config_file.yaml');
    }
}
