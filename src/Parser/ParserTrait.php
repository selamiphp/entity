<?php

namespace Selami\Entity\Parser;

use Selami\Entity\Exception\FileNotFoundException;
use InvalidArgumentException;

trait ParserTrait
{
    protected $schemaConfig;

    /**
     * Set config string
     * @param string $configString
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function setConfig(string $configString)
    {
        $this->isConfigEmpty($configString);
        $this->schemaConfig = $configString;
    }

    /**
     * Set the file contains config string
     * @param string $configFile
     * @return mixed
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function getConfigFromFile(string $configFile)
    {
        if (!file_exists($configFile)) {
            $message = sprintf(
                'File %s could be found',
                $configFile
            );
            throw new FileNotFoundException($message);
        }
        $configString = file_get_contents($configFile);
        $this->setConfig($configString);
    }

    /**
     * Checks if schemaConfig is empty
     * @param string $configString
     * @throws InvalidArgumentException
     */
    protected function isConfigEmpty(string $configString)
    {
        if (empty($configString)) {
            throw new InvalidArgumentException('Schema config can\'t be empty');
        }
    }
}
