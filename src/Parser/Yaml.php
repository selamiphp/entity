<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;
use UnexpectedValueException;
use Symfony\Component\Yaml as SymfonyYaml;
use Symfony\Component\Yaml\Exception\ParseException as SymfonyParseException;

/**
 * Yaml Parser
 *
 * @package Selami\Entity\Parser
 */
class Yaml implements ParserInterface
{
    use ParserTrait;

    private $yamlParser = 'ext';

    private static $yamlParsers = ['ext','symfony'];

    /**
     * Yaml constructor.
     * @param string $yamlParser
     * @param  string $schemaConfig
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function __construct(string $yamlParser = 'ext', string $schemaConfig = null)
    {
        $this->setYamlParser($yamlParser);
        if ($schemaConfig !== null) {
            $this->setConfig($schemaConfig);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        $this->isConfigEmpty($this->schemaConfig);
        $schema = $this->yamlParse($this->schemaConfig);
        return $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function checkFormat()
    {
        try {
            $res = SymfonyYaml\Yaml::parse($this->schemaConfig);
            return true;
        } catch (SymfonyParseException $e) {
            return false;
        }
    }

    /**
     * @param string $selectedParser
     * @throws UnexpectedValueException
     */
    private function setYamlParser(string $selectedParser = 'ext')
    {
        if (!in_array($selectedParser, self::$yamlParsers, true)) {
            throw new UnexpectedValueException('Invalid parser. Possible values are: '
                . implode(', ', self::$yamlParsers));
        }
        $yamlParser = 'symfony';
        if (($selectedParser === 'ext') && extension_loaded('yaml')) {
            $yamlParser = 'ext';
        }
        $this->yamlParser = $yamlParser;
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    private function yamlParse()
    {
        if ($this->yamlParser === 'ext') {
            return $this->extParse();
        }
        return $this->symfonyParse();
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    private function extParse()
    {
        try {
            return yaml_parse($this->schemaConfig);
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    private function symfonyParse()
    {
        try {
            return SymfonyYaml\Yaml::parse($this->schemaConfig);
        } catch (SymfonyParseException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}
