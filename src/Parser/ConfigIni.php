<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use UnexpectedValueException;
use Selami\Entity\Exception\FileNotFoundException;


/**
 * Config Ini Parser
 * @package Selami\Entity\Parser
 */
class ConfigIni implements ParserInterface
{
    protected $schemaConfig;

    /**
     * Config constructor.
     * @param string $schemaConfig
     * @param bool $is_file
     * @throws FileNotFoundException
     */
    public function __construct(string $schemaConfig, bool $is_file = false)
    {
        if ($is_file && !file_exists($schemaConfig)) {
            $message = sprintf('File: %s not found. please provide full path for file names', $schemaConfig);
            throw new FileNotFoundException($message);
        }
        $this->schemaConfig = $is_file ? file_get_contents($schemaConfig) : $schemaConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        $schema = @parse_ini_string($this->schemaConfig, true);
        if ($schema === false) {
            $message = error_get_last();
            throw new UnexpectedValueException($message['message']);
        }
        return ['schema' => $schema];
    }

    /**
     * {@inheritDoc}
     */
    public function checkFormat()
    {
        $schema = @parse_ini_string($this->schemaConfig, true);
        return $schema !== false;
    }
}
