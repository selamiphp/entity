<?php
declare(strict_types=1);


namespace Selami\Entity\Parser;

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
        if (!in_array($parserType, self::$validParsers, true)) {
            $message = sprintf(
                'Invalid parser type: %s. Valid types are case sensitive and possible values are: %s',
                $parserType,
                implode(', ', array_keys(self::$validParsers))
            );
            throw new UnexpectedValueException($message);
        }
        return new self::$validParsers[$parserType]();
    }
}
