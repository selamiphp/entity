<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Parser\PhpArray;
use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;

class MyPhpArrayParserClass extends \PHPUnit_Framework_TestCase
{
    protected $validSchema;

    protected $invalidSchema ;

    protected $imaginaryFile = '/tmp/imaginary_config_file.php';


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
        $parser = new PhpArray($this->validSchema);
        $this->assertInstanceOf(ParserInterface::class, $parser);
        $schema = $parser->parse();
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
    public function shouldThrowInvalidArgumentExceptionForInvalidArray()
    {
        $parser = new PhpArray($this->invalidSchema[0]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForPhpParseError()
    {
        $parser = new PhpArray($this->invalidSchema[1]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForPhpTypeError()
    {

        $parser = new PhpArray($this->invalidSchema[2]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForPhpDivisionByZeroError()
    {
        $parser = new PhpArray($this->invalidSchema[3]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForPhpArithmeticError()
    {

        $parser = new PhpArray($this->invalidSchema[4]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForPhpAssertionError()
    {

        $parser = new PhpArray($this->invalidSchema[5]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     *
     */
    public function shouldThrowInvalidArgumentExceptionForInvalidReturnArray()
    {
        $parser = new PhpArray($this->invalidSchema[6]);
        $parser->parse();
    }

    /**
     * @test
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldThrowFileNotFoundExceptionForInvalidArray()
    {
        $parser = new PhpArray();
        $parser->getConfigFromFile($this->imaginaryFile);
    }


    /**
     * @test
     */
    public function shouldReturnTrueForCheckFormatMethod()
    {
        $parser = new PhpArray($this->validSchema);
        $isFormatOk = $parser->checkFormat();
        $this->assertTrue($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodForInvalidArray()
    {
        $parser = new PhpArray($this->invalidSchema[0]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodForPhpParseError()
    {
        $parser = new PhpArray($this->invalidSchema[1]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodForPhpTypeError()
    {
        $parser = new PhpArray($this->invalidSchema[2]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodForPhpDivisionByZeroError()
    {
        $parser = new PhpArray($this->invalidSchema[3]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodForPhpArithmeticError()
    {
        $parser = new PhpArray($this->invalidSchema[4]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodforPhpAssertionError()
    {
        $parser = new PhpArray($this->invalidSchema[5]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForCheckFormatMethodForInvalidReturnArray()
    {
        $parser = new PhpArray($this->invalidSchema[6]);
        $isFormatOk = $parser->checkFormat();
        $this->assertFalse($isFormatOk);
    }
}
