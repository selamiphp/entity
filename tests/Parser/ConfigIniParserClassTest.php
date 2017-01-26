<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\ConfigIni;
use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyConfigIniParserClass extends TestCase
{
    protected $validSchema = '

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

[is_active]
    type = enum
    length = 1
    options[] = 0
    options[] = 1
';

    protected $invalidSchema = '
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
        $parser = new ConfigIni();
        $parser->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/config.ini');
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
    public function shouldThrowInvalidArgumentExceptionForInvalidConfigIni()
    {
        $parser = new ConfigIni($this->invalidSchema);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidConfigIni()
    {
        $parser = new ConfigIni();
        $parser->getConfigFromFile('/tmp/not_existed_config_file');
    }


    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $parser = new ConfigIni($this->validSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethod()
    {
        $parser = new ConfigIni($this->invalidSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}
