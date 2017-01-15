<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use Seld\JsonLint\JsonParser;
use InvalidArgumentException;

/**
 * Json Parser
 *
 * @package Selami\Entity\Parser
 */
class Json implements ParserInterface
{
    use ParserTrait;

    /**
     * Json constructor.
     *
     * @param  string $schemaConfig
     * @throws InvalidArgumentException
     */
    public function __construct(string $schemaConfig=null)
    {
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
        $schema = json_decode($this->schemaConfig, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $parser = new JsonParser();
            $linting = $parser->lint($this->schemaConfig);
            throw new InvalidArgumentException($linting->getMessage());
        }
        return $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function checkFormat()
    {
        $parser = new JsonParser();
        $linting = $parser->lint($this->schemaConfig);
        return $linting === null;
    }
}
