<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;

/**
 * Config Ini Parser
 *
 * @package Selami\Entity\Parser
 */
class ConfigIni implements ParserInterface
{
    use ParserTrait;

    /**
     * Config constructor.
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
        $schema = @parse_ini_string($this->schemaConfig, true);
        if ($schema === false) {
            $message = error_get_last();
            throw new InvalidArgumentException($message['message']);
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
