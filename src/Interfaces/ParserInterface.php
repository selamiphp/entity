<?php
declare(strict_types=1);

namespace Selami\Entity\Interfaces;

use InvalidArgumentException;
use Selami\Entity\Exception\FileNotFoundException;

/**
 * Interface ParserInterface
 * @package Selami\Entity\Interfaces
 */
interface ParserInterface
{

    /**
     * Sets config string
     * @param string $configString
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function setConfig(string $configString);

    /**
     * Sets the file contains config string, reads file and sets $schemaConfig
     * @param string $configFile
     * @return mixed
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function getConfigFromFile(string $configFile);

    /**
     * Parse config string
     * @return array
     * @throws InvalidArgumentException
     */
    public function parse();

    /**
     * Checks config string format. Returns true if format is valid, false otherwise.
     * @return bool
     */
    public function checkFormat();
}
