<?php
declare(strict_types=1);

namespace Selami\Entity\Parser;

use Selami\Entity\Interfaces\ParserInterface;
use InvalidArgumentException;
use Selami\Entity\Exception\FileNotFoundException;

/**
 * Array Parser
 *
 * @package Selami\Entity\Parser
 */
class PhpArray implements ParserInterface
{
    use ParserTrait;

    protected $configFile;

    /**
     * PhpArray constructor.
     * @param string $configFileName
     * @throws FileNotFoundException
     */
    public function __construct(string $configFileName)
    {
        if (!file_exists($configFileName)) {
            throw new FileNotFoundException('File %s couldn\t be found.');
        }
        $this->configFile = $configFileName;
    }

    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        try {
            $schema = include $this->configFile;
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (\Error $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        if (!is_array($schema)) {
            throw new InvalidArgumentException('Config file does not return an array.');
        }
        return $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function checkFormat()
    {
        try {
            $schema = include $this->configFile;
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
