<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;
use SimpleXMLElement;

/**
 * XML Parser
 *
 * @package Selami\Entity\Parser
 */
class Xml implements ParserInterface
{
    use ParserTrait;

    /**
     * Config constructor.
     *
     * @param  string $schemaConfig
     * @throws InvalidArgumentException
     *
     */
    public function __construct(string $schemaConfig = null)
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
        try {
            $schemaData = new SimpleXMLElement($this->schemaConfig);
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        $schema = $this->checkSchemaData($schemaData);
        return ['schema' => $schema];
    }

    /**
     * {@inheritDoc}
     */
    public function checkFormat()
    {
        try {
            new SimpleXMLElement($this->schemaConfig);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    private function checkSchemaData($schemaData)
    {
        if ($schemaData instanceof SimpleXMLElement) {
            return $this->arrayWalkRecursively((array) $schemaData);
        }
        return [];
    }

    private function arrayWalkRecursively(array $schemaData)
    {
        foreach ($schemaData as $key => $value) {
            $newValue = null;
            if ($value instanceof SimpleXMLElement) {
                $newValue = (array) $value;
                $newValue = $this->arrayWalkRecursively($newValue);
            }
            $schemaData[$key] = $newValue===null ? $value: $newValue;
        }
        return $schemaData;
    }
}
