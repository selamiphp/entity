<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use UnexpectedValueException;
use Selami\Entity\Exception\FileNotFoundException;


/**
 * Array Parser
 * @package Selami\Entity\Parser
 */
class PhpArray implements ParserInterface
{
    protected $schemaConfig;

    /**
     * Config constructor.
     * @param string $schemaConfig
     * @throws FileNotFoundException
     */
    public function __construct(string $schemaConfig)
    {
        if (!file_exists($schemaConfig)) {
            $message = sprintf('File: %s not found. please provide full path for file names', $schemaConfig);
            throw new FileNotFoundException($message);
        }
        $this->schemaConfig =  $schemaConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        //$errorReportingValue = ini_get('error_reporting');
        //error_reporting(-1);
        try {
            $schema = require $this->schemaConfig;
        } catch (\Exception $e) {
            throw new UnexpectedValueException($e->getMessage());
        } catch (\Error $e) {
            throw new UnexpectedValueException($e->getMessage());
        }
        finally {
           // error_reporting($errorReportingValue);
        }
        if (!is_array($schema)) {
            throw new UnexpectedValueException('Config file does not return an array.');
        }
        return $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function checkFormat()
    {
        try {
            $schema = require $this->schemaConfig;
            if (!is_array($schema)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            // will return false
        } catch (\Error $e) {
            // will return false
        }
        return false;

    }
}
