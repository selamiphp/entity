<?php
declare(strict_types=1);


namespace Selami\Entity\Parser;

use InvalidArgumentException;

class ParserFactory
{
    /**
     * @var array
     */
    protected static $validParsers = [
        'ini'   => ConfigIni::class,
        'json'  => Json::class,
        'yaml'  => Yaml::class,
        'xml'   => Xml::class,
        'php'   => PhpArray::class
    ];
    public static function createParser(string $parserType)
    {
        if (!array_key_exists($parserType, self::$validParsers)) {
            $message = sprintf(
                'Invalid parser type: %s. Valid types are case sensitive and possible values are: %s',
                $parserType,
                implode(', ', array_keys(self::$validParsers))
            );
            throw new InvalidArgumentException($message);
        }
        return new self::$validParsers[$parserType]();
    }
}
