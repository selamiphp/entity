<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use Seld\JsonLint\JsonParser;
use UnexpectedValueException;
use Selami\Entity\Exception\FileNotFoundException;

/**
 * Json Parser
 * @package Selami\Entity\Parser
 */
class Json implements ParserInterface
{
    protected $schemaConfig;

    /**
     * Json constructor.
     * @param string $schemaConfig
     * @param bool $isFile
     * @throws FileNotFoundException
     */
    public function __construct(string $schemaConfig, bool $isFile = false)
    {
        if ($isFile && !file_exists($schemaConfig)) {
            $message = sprintf('File: %s not found. please provide full path for file names', $schemaConfig);
            throw new FileNotFoundException($message);
        }
        $this->schemaConfig = $isFile ? file_get_contents($schemaConfig) : $schemaConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        $schema = json_decode($this->schemaConfig, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $parser = new JsonParser();
            $linting = $parser->lint($this->schemaConfig);
            throw new UnexpectedValueException($linting->getMessage());
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
