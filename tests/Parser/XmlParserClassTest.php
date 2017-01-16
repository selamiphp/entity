<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\Xml;
use Selami\Entity\Interfaces\ParserInterface;

class MyXmlParserClass extends \PHPUnit_Framework_TestCase
{

    protected $validSchema = '<?xml version="1.0"?>
<schema>
    <id>
        <type>int</type>
        <length>3</length>
        <min>0</min>
        <max>999</max>
    </id>
    <age>
        <type>int</type>
        <length>3</length>
        <min>0</min>
        <max>120</max>
    </age>
    <is_active>
        <type>enum</type>
        <length>1</length>
        <options>
            <value>0</value>
            <value>1</value>
        </options>
    </is_active>
</schema>
';

    protected $invalidSchema = '<?xml version="1.0"?>
<schema>
    <id>
        <type>int</type>
        <length>3</length>
        <min>0</min>
        <max>999
    </id>
    <age>
        <type>int</type>
        <length>3</length>
        <min>0</min>
        <max>120</max>
    </age>
    <is_active>
        <type>enum</type>
        <length>1</length>
        <options>
            <value>0</value>
            <value>1</value>
        </options>
    </is_active>
</schema>';


    /**
     * @test
     */
    public function shouldReturnArrayCorrectly()
    {
        $parser = new Xml();
        $parser->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/config.xml');
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
    public function shouldThrowInvalidArgumentExceptionForInvalidXml()
    {
        $parser = new Xml($this->invalidSchema);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidXml()
    {
        $parser = new Xml();
        $parser->getConfigFromFile('/tmp/not_existed_config_file');
    }

    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $parser = new Xml($this->validSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertTrue($isFormatOk);
    }
    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethod()
    {
        $parser = new Xml($this->invalidSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}
