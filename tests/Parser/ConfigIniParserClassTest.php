<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\ConfigIni;
use Selami\Entity\Interfaces\ParserInterface;
use UnexpectedValueException;

class MyConfigIniParserClass extends  \PHPUnit_Framework_TestCase
{
    protected  $validSchema = '

[id]
    type = int
    length = 3
    min = 0
    max = 999

[age]
    type = int
    length = 3
    min = 0
    max = 122

[age]
    type = enum
    length = 1
    options[] = 0
    options[] = 1
';

    protected  $invalidSchema = '
[id]
type = int
length = 3
min = 0
max = 999

[age
type = "int" #invalid comment block
length = 3
min = 0
max = 122

[age]
type = enum
length = 1
options[] = 0
options[] = 1

';

    /**
     * @test
     */
    public function shouldReturnArrayCorrectly()
    {
        $serializer = new ConfigIni(dirname(__DIR__) . '/resources/config_data/config.ini', true);
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
    public function shouldThrowUnexpectedValueExceptionForInvalidConfigIni()
    {
        $serializer = new ConfigIni($this->invalidSchema, false);
        $serializer->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidConfigIni()
    {
        new ConfigIni($this->validSchema, true);
    }


    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $serializer = new ConfigIni($this->validSchema, false);
        $isFormatOk = $serializer->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethod()
    {
        $serializer = new ConfigIni($this->invalidSchema, false);
        $isFormatOk = $serializer->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}