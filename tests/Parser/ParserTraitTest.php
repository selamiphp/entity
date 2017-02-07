<?php
declare(strict_types=1);

namespace tests;

use Selami\Entity\Exception\FileNotFoundException;
use Selami\Entity\Parser\ParserTrait;
use InvalidArgumentException;

class MyParserTrait extends \PHPUnit\Framework\TestCase
{


    /**
     * @test
     */
    public function shouldInitiallyEmpty()
    {
        $parseTrait = $this->getObjectForTrait(ParserTrait::class);

        $this->assertAttributeEmpty('schemaConfig', $parseTrait);

        return $parseTrait;
    }

    /**
     * @test
     * @cover ParserTrait::setConfig
     * @depends shouldInitiallyEmpty
     * @param ParseTrait $parseTrait
     */
    public function shouldSetConfigSuccessfully($parseTrait)
    {
        $parseTrait->setConfig('random_text');
        $this->assertAttributeEquals(
            'random_text',
            'schemaConfig',
            $parseTrait
        );
    }

    /**
     * @test
     * @cover  ParserTrait::setConfig
     * @depends shouldInitiallyEmpty
     * @param ParseTrait $parseTrait
     * @expectedException InvalidArgumentException
     */
    public function shouldFailSettingEmptyConfig($parseTrait)
    {
        $parseTrait->setConfig('');
        $this->assertAttributeEquals(
            'random_text',
            'schemaConfig',
            $parseTrait
        );
    }

    /**
     * @test
     * @cover  ParserTrait::getConfigFromFile
     * @depends shouldInitiallyEmpty
     * @param ParseTrait $parseTrait
     */
    public function shouldGetConfigFromFileSuccessfully($parseTrait)
    {
        $parseTrait->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/config_for_trait.txt');
        $this->assertAttributeEquals(
            'random_text_from_file',
            'schemaConfig',
            $parseTrait
        );
    }

    /**
     * @test
     * @cover  ParserTrait::getConfigFromFile
     * @depends shouldInitiallyEmpty
     * @param ParseTrait $parseTrait
     * @expectedException InvalidArgumentException
     */
    public function shouldFailGettingConfigFromFile($parseTrait)
    {
        $parseTrait->getConfigFromFile(dirname(__DIR__) . '/resources/config_data/empty_config_for_trait.txt');
        $this->assertAttributeEquals(
            'random_text_from_file',
            'schemaConfig',
            $parseTrait
        );
    }

    /**
     * @test
     * @cover  ParserTrait::getConfigFromFile
     * @depends shouldInitiallyEmpty
     * @param ParseTrait $parseTrait
     * @expectedException Selami\Entity\Exception\FileNotFoundException
     */
    public function shouldNotFoundNonExistedConfigFromFile($parseTrait)
    {
        $parseTrait->getConfigFromFile(dirname(__DIR__) . '/tmp/imaginary_trait_config_file.txt');
        $this->assertAttributeEquals(
            'random_text_from_file',
            'schemaConfig',
            $parseTrait
        );
    }
}
